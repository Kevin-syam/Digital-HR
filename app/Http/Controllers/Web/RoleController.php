<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use App\Requests\Role\RoleRequest;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  RoleController extends Controller
{
    private $view = 'admin.role.';

    private RoleRepository $roleRepo;
    private UserRepository $userRepo;

    public function __construct(RoleRepository $roleRepo, UserRepository $userRepo)
    {
        $this->roleRepo = $roleRepo;
        $this->userRepo = $userRepo;
    }

    public function index()
    {
        $this->authorize('list_role');
        try {
            $roles = $this->roleRepo->getAllUserRoles();
            return view($this->view . 'index', compact('roles'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
        $this->authorize('create_role');
        try {
            return view($this->view . 'create');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function store(RoleRequest $request)
    {
        $this->authorize('create_role');
        try {
            $validatedData = $request->validated();
            DB::beginTransaction();
            $this->roleRepo->store($validatedData);
            DB::commit();
            Artisan::call('cache:clear');
            return redirect()->back()->with('success', 'New Role Added Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('danger', $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        $this->authorize('edit_role');
        try {
            $roleDetail = $this->roleRepo->getRoleById($id);
            if (!$roleDetail) {
                throw new Exception('Role Detail Not Found', 204);
            }
            if ($roleDetail->slug == 'admin') {
                throw new Exception('Cannot Edit Admin Role', 402);
            }
            return view($this->view . 'edit', compact('roleDetail'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function update(RoleRequest $request, $id)
    {
        $this->authorize('edit_role');
        try {
            $validatedData = $request->validated();
            $roleDetail = $this->roleRepo->getRoleById($id);
            if (!$roleDetail) {
                throw new Exception('Role Detail Not Found', 404);
            }
            DB::beginTransaction();
            $this->roleRepo->update($roleDetail, $validatedData);
            DB::commit();
            Artisan::call('cache:clear');
            return redirect()->back()->with('success', 'Role Detail Updated Successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage())
                ->withInput();
        }

    }

    public function toggleStatus($id)
    {
        $this->authorize('edit_role');
        try {
            DB::beginTransaction();
                $this->roleRepo->toggleStatus($id);
            DB::commit();
            return redirect()->back()->with('success', 'Status changed  Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function delete($id)
    {
        $this->authorize('delete_role');
        try {
            $roleDetail = $this->roleRepo->getRoleById($id);
            if (!$roleDetail) {
                throw new Exception('Role Detail Not Found', 404);
            }
            if ($roleDetail->slug == 'admin') {
                throw new Exception('Cannot Delete Admin Role', 402);
            }
            $user = $this->userRepo->findUserDetailByRole($id);
            {
                if ($user) {
                    throw new Exception('Cannot Delete Assigned Role', 402);
                }
            }
            DB::beginTransaction();
            $this->roleRepo->delete($roleDetail);
            DB::commit();
            Artisan::call('cache:clear');
            return redirect()->back()->with('success', 'Role Detail Deleted  Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function createPermission($roleId): Factory|View|RedirectResponse|Application
    {
        $this->authorize('list_permission');
        try {
            $selectPermissionGroup = ['*'];
            $selectRole = ['id', 'name', 'slug'];
            $withPermissionType = ['permissionGroups','permissionGroups.getPermission'];
            $withRole = ['permission'];
            $permissionGroupTypeList = $this->roleRepo->getPermissionGroupTypeDetails($selectPermissionGroup, $withPermissionType);
            $role = $this->roleRepo->getRoleById($roleId, $selectRole, $withRole);
            $allRoles = $this->roleRepo->getAllRolesExceptAdmin();
            if (!$role) {
                throw new Exception('Role Detail Not Found', 404);
            }
            if($role->slug == 'admin'){
                throw new Exception('Admin Role Is Always Assigned With All Permission', 404);
            }
            $isEdit = false;
            $role_permission = [];
            if ($role->getRolePermission->count() > 0) {
                $role_permission = $role->getRolePermission->pluck('permission_id')->toArray();
                $isEdit = true;
            }
            return view($this->view . 'permission', compact('permissionGroupTypeList',
                'role',
                'role_permission', 'isEdit','allRoles'
            ));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function assignPermissionToRole(Request $request, $roleId): RedirectResponse
    {
        $this->authorize('assign_permission');
        try {
            $data = $request->all();
            $role = $this->roleRepo->getRoleById($roleId);
            $validatedPermissionData = $data['permission_value'] ?? [];
            DB::beginTransaction();
            $this->roleRepo->syncPermissionToRole($role, $validatedPermissionData);
            DB::commit();
            return redirect()->back()->with('success', 'Permission Updated To Role Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\CompanyRepository;
use App\Repositories\OfficeTimeRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserAccountRepository;
use App\Repositories\UserRepository;
use App\Requests\User\ChangePasswordRequest;
use App\Requests\User\UserAccountRequest;
use App\Requests\User\UserCreateRequest;
use App\Requests\User\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;

class UserController extends Controller
{
    private $view ='admin.users.';

    private UserRepository $userRepo;
    private CompanyRepository $companyRepo;
    private RoleRepository $roleRepo;
    private OfficeTimeRepository $officeTimeRepo;
    private UserAccountRepository $accountRepo;


    public function __construct(UserRepository $userRepo,
                                CompanyRepository $companyRepo,
                                RoleRepository $roleRepo,
                                OfficeTimeRepository $officeTimeRepo,
                                UserAccountRepository $accountRepo

    )
    {
        $this->userRepo = $userRepo;
        $this->companyRepo = $companyRepo;
        $this->roleRepo = $roleRepo;
        $this->officeTimeRepo = $officeTimeRepo;
        $this->accountRepo = $accountRepo;
    }

    public function index(Request $request)
    {
        $this->authorize('list_employee');
        try {
            $filterParameters = [
                'employee_name' => $request->employee_name ?? null,
                'email' => $request->email ?? null,
                'phone' => $request->phone ?? null,
            ];
            $with = ['branch:id,name','company:id,name','post:id,post_name','department:id,dept_name','role:id,name'];
            $select=['users.*','branch_id','company_id','department_id','post_id','role_id'];
            $users = $this->userRepo->getAllUsers($filterParameters,$select,$with);
            return view($this->view . 'index',compact('users','filterParameters'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
        $this->authorize('create_employee');
        try {
            $with = ['branches:id,name'];
            $select = ['id','name'];
            $companyDetail = $this->companyRepo->getCompanyDetail($select,$with);
            $roles = $this->roleRepo->getAllActiveRoles();
            return view($this->view.'create',compact('companyDetail','roles'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function store(UserCreateRequest $request,UserAccountRequest $accountRequest)
    {
        $this->authorize('create_employee');
        try{
            $validatedData = $request->validated();
            $accountValidatedData = $accountRequest->validated();

            $validatedData['password'] = bcrypt($validatedData['password']);
            DB::beginTransaction();
                $user = $this->userRepo->store($validatedData);
                $accountValidatedData['user_id'] = $user['id'];
                $this->accountRepo->store($accountValidatedData);
            DB::commit();
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'New Employee Detail Added Successfully');
        }catch(\Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $this->authorize('show_detail_employee');
        try {
            $with = [
                'branch:id,name',
                'company:id,name',
                'post:id,post_name',
                'department:id,dept_name',
                'role:id,name',
                'accountDetail'
            ];
            $select = ['users.*', 'branch_id', 'company_id', 'department_id', 'post_id', 'role_id'];
            $userDetail = $this->userRepo->findUserDetailById($id,$select,$with);
            return view($this->view.'show2',compact('userDetail'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getFile());
        }
    }

    public function edit($id)
    {
        $this->authorize('edit_employee');
        try {
            $with = ['branches:id,name'];
            $select = ['id','name'];
            $companyDetail = $this->companyRepo->getCompanyDetail($select,$with);
            $roles = $this->roleRepo->getAllActiveRoles();
            $userSelect = ['*'];
            $userWith = ['accountDetail'];
            $userDetail = $this->userRepo->findUserDetailById($id,$userSelect,$userWith);
            return view($this->view.'edit',compact('companyDetail','roles','userDetail'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getFile());
        }
    }

    public function update(UserUpdateRequest $request, UserAccountRequest $accountRequest, $id)
    {
        $this->authorize('edit_employee');
        try{
            $validatedData = $request->validated();
            $accountValidatedData = $accountRequest->validated();
            $userDetail = $this->userRepo->findUserDetailById($id);
            if(in_array($userDetail->username,User::DEMO_USERS_USERNAME)){
                throw new Exception('This is a demo version. Please buy the application to use the full feature',400);
            }
            if(!$userDetail){
                throw new \Exception('User Detail Not Found',404);
            }
            DB::beginTransaction();
                $this->userRepo->update($userDetail,$validatedData);
                $this->accountRepo->createOrUpdate($userDetail,$accountValidatedData);
            DB::commit();
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User Detail Updated Successfully');
        }catch(Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        $this->authorize('edit_employee');
        try {
            DB::beginTransaction();
                $this->userRepo->toggleIsActiveStatus($id);
            DB::commit();
            return redirect()->back()->with('success', 'Users Is Active Status Changed  Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function delete($id)
    {
        $this->authorize('delete_employee');
        try {
            $usersDetail = $this->userRepo->findUserDetailById($id);
            if(in_array($usersDetail->username, User::DEMO_USERS_USERNAME)){
                throw new Exception('This is a demo version. Please buy the application to use the full feature',400);
            }
            if (!$usersDetail) {
                throw new \Exception('Users Detail Not Found', 404);
            }
            if($usersDetail->id == auth()->user()->id){
                throw new Exception('cannot delete own records',402);
            }
            DB::beginTransaction();
            $this->userRepo->delete($usersDetail);
            DB::commit();
            return redirect()->back()->with('success', 'User Detail Removed Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function changeWorkSpace($id)
    {
        $this->authorize('edit_employee');
        try {
            $select = ['id','workspace_type'];
            $userDetail = $this->userRepo->findUserDetailById($id,$select);
            if (!$userDetail) {
                throw new \Exception('Users Detail Not Found', 404);
            }
            DB::beginTransaction();
                $this->userRepo->changeWorkSpace($userDetail);
            DB::commit();
            return redirect()->back()->with('success', 'User Workspace Changed Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getAllCompanyEmployeeDetail($companyId)
    {
        try{
            $selectEmployee=['id','name'];
            $selectOfficeTime = ['id','opening_time','closing_time'];
            $employees = $this->userRepo->getAllVerifiedEmployeeOfCompany($selectEmployee);
            $officeTime = $this->officeTimeRepo->getALlActiveOfficeTimeByCompanyId($companyId,$selectOfficeTime);
            return response()->json([
                'employee' => $employees,
                'officeTime' => $officeTime
            ]);
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function changePassword(ChangePasswordRequest $request,$userId)
    {
        $this->authorize('change_password');
        try{
            $validatedData = $request->validated();
            $userDetail = $this->userRepo->findUserDetailById($userId);
            if(in_array($userDetail->username, User::DEMO_USERS_USERNAME)){
                throw new Exception('This is a demo version. Please buy the application to use the full feature',400);
            }
            if (!$userDetail) {
                throw new \Exception('Users Detail Not Found', 404);
            }
            DB::beginTransaction();
                $this->userRepo->changePassword($userDetail,$validatedData['new_password']);
            DB::commit();
            return redirect()->back()->with('success', 'User Password Changed Successfully');

        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function forceLogOutEmployee($employeeId)
    {
        $this->authorize('force_logout');
        try{
            $tokenRepository = app(TokenRepository::class);
            $refreshTokenRepository = app(RefreshTokenRepository::class);

            $userDetail = $this->userRepo->findUserDetailById($employeeId);
            if(!$userDetail){
                throw new Exception('User Detail Not found',404);
            }
            $accessToken = $userDetail->tokens;
            DB::beginTransaction();
                foreach ($accessToken as $token) {
                    $tokenRepository->revokeAccessToken($token->id);
                    $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
                }
                $validatedData['uuid'] = null;
                $validatedData['logout_status'] = 0;
                $validatedData['remember_token'] = null;
                $validatedData['fcm_token'] = null;
                $this->userRepo->update($userDetail,$validatedData);
            DB::commit();
            return redirect()->back()->with('success', 'Force log out successFull');
        }catch(Exception $exception){
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}

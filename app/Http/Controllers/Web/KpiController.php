<?php

namespace App\Http\Controllers\Web;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Kpi;
use App\Models\User;
use App\Repositories\BranchRepository;
// use App\Repositories\CompanyRepository;
use App\Repositories\DepartmentRepository;
// use App\Repositories\UserRepository;
use App\Repositories\PostRepository;
use App\Repositories\KpiRepository;
use App\Requests\Department\DepartmentStoreRequest;
use App\Requests\Kpi\KpiRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KpiController extends Controller
{
    private $view = 'admin.manageKpi.';

    private DepartmentRepository $departmentRepo;
    // private CompanyRepository $companyRepo;
    // private UserRepository $userRepo;
    private BranchRepository $branchRepo;
    private KpiRepository $kpiRepo;

    public function __construct(DepartmentRepository $departmentRepo,
                                BranchRepository $branchRepo,
                                KpiRepository $kpisRepo)
    {
        $this->departmentRepo = $departmentRepo;
        // $this->companyRepo = $companyRepo;
        // $this->userRepo = $userRepo;
        $this->branchRepo = $branchRepo;
        $this->kpiRepo = $kpisRepo;
    }

    public function index(Request $request)
    {
        $this->authorize('list_kpi');
        try {
            $filterParameters = [
                'name' =>  $request->name ?? null,
                'department' => $request->department ?? null
            ];
            $kpiSelect = ['*'];
            $with = ['department:id,dept_name'];
            $departments = $this->departmentRepo->pluckAllDepartments();
            $kpis = $this->kpiRepo->getAllKpis($filterParameters,$with,$kpiSelect);
            return view($this->view . 'index', compact('kpis',
                'filterParameters',
                'departments'));
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function create()
    {
        $this->authorize('create_kpis');
        try {
            $with = [];
            $select = ['id', 'dept_name'];
            $departmentDetail = $this->departmentRepo->getAllActiveDepartments($with, $select);
            return view($this->view . 'create', compact('departmentDetail'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function getKPIs(Request $request)
    {
        $departmentId = $request->input('department');
        // Fetch KPIs from the database based on the selected department
        $kpis = Kpi::where('department_id', $departmentId)->get();
        return response()->json($kpis);
    }


    // public function getAllKpisByBranchId($deptId)
    // {
    //     try {
    //         $with = [];
    //         $select = ['kpi_desc', 'id'];
    //         $posts = $this->postRepo->getAllActivePostsByDepartmentId($deptId,$with,$select);
    //         return response()->json([
    //             'data' => $posts
    //         ]);
    //     } catch (Exception $exception) {
    //         return AppHelper::sendErrorResponse($exception->getMessage(),$exception->getCode());;
    //     }
    // }

    public function store(KpiRequest $request)
    {
        $this->authorize('create_kpis');
        try {
            $validatedData = $request->validated();
            DB::beginTransaction();
            // $this->kpiRepo->store($validatedData);
            foreach ($validatedData['kpi_desc'] as $index => $kpiDesc) {
                $kpiData = [
                    'kpi_desc' => $kpiDesc,
                    'dept_id' => $validatedData['dept_id'][$index],
                    'weight' => $validatedData['weight'][$index],
                    'kpi_target' => $validatedData['kpi_target'][$index],
                    'unit' => $validatedData['unit'][$index],
                    'is_max' => $validatedData['is_max'][$index],
                ];
                // if (isset($validatedData['dept_id'][$index])) {
                //     $kpiData['dept_id'] = $validatedData['dept_id'][$index];
                // }
                $this->kpiRepo->store($kpiData);
            }
            DB::commit();
            return redirect()->route('admin.manageKpi.index')->with('success', 'New Post Added Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('danger', $e->getMessage())
                ->withInput();
        }
    }

    public function delete($id)
    {
        $this->authorize('delete_kpi');
        try {
            $kpiDetail = $this->kpiRepo->getKpiById($id);
            if (!$kpiDetail) {
                throw new \Exception('KPI Detail Not Found', 404);
            }
            DB::beginTransaction();
                $this->kpiRepo->delete($kpiDetail);
            DB::commit();
            return redirect()->back()->with('success', 'KPI Detail Deleted  Successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }
}

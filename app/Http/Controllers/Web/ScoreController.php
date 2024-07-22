<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\ScoreKpi;
use App\Repositories\DepartmentRepository;
use App\Repositories\KpiRepository;
use App\Repositories\PostRepository;
use App\Repositories\scoreKpiRepository;
use Illuminate\Support\Facades\DB;

class ScoreController extends Controller
{
    //
    private $view = 'admin.scoreKpi.';

    private PostRepository $postRepo;
    private DepartmentRepository $departmentRepo;
    private KpiRepository $kpiRepo;
    private scoreKpiRepository $scoreKpiRepo;

    public function __construct(PostRepository $postRepo, DepartmentRepository $departmentRepo, KpiRepository $kpiRepo, scoreKpiRepository $scoreKpiRepo)
    {
        $this->postRepo = $postRepo;
        $this->departmentRepo = $departmentRepo;
        $this->kpiRepo = $kpiRepo;
        $this->scoreKpiRepo = $scoreKpiRepo;
    }


    public function index(Request $request)
    {
        $this->authorize('list_scoreKpi');
        try {
            $filterParameters = [
                'name' =>  $request->name ?? null,
                'department' => $request->department ?? null
            ];
            $kpiSelect = ['*'];
            $scoreSelect = ['dept_id', 'period', DB::raw('SUM(score) as total_score')];
            $with = ['department:id,dept_name'];
            
            $departments = $this->departmentRepo->pluckAllDepartments();
            $score = $this->scoreKpiRepo->getAllScoreKpis($filterParameters,$with,$scoreSelect);
            $detailedScores = $this->scoreKpiRepo->getDetailedScore('kpi');
            $kpi = $this->kpiRepo->getAllKpis($filterParameters,$with,$kpiSelect);
            return view($this->view . 'index', compact('score','kpi',
                'filterParameters',
                'departments', 'detailedScores'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    // public function create()
    // {
    //     $this->authorize('list_scoreKpi');
    //     try {
    //         $with = [];
    //         $select = ['id', 'dept_name'];
    //         // $departments = $this->departmentRepo->pluckAllDepartments();
    //         $departmentDetail = $this->departmentRepo->getAllActiveDepartments($with, $select);
            
    //         return view($this->view . 'create', compact('departmentDetail'));
    //     } catch (\Exception $exception) {
    //         return redirect()->back()->with('danger', $exception->getMessage());
    //     }
    // }

    public function create()
    {
        $this->authorize('create_scores');
        try {
            $with = [];
            $select = ['id', 'dept_name'];
            // $departments = $this->departmentRepo->pluckAllDepartments();
            $departmentDetail = $this->departmentRepo->getAllActiveDepartments($with, $select);
            
            return view($this->view . 'create', compact('departmentDetail'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
        // $this->authorize('list_scoreKpi');
        // try {
        //     $departments = Department::with('kpis')->where('is_active', 1)->get();
        //     return view('kpi.create', compact('departments'));
        // } catch (\Exception $exception) {
        //     return redirect()->back()->with('danger', $exception->getMessage());
        // }
    }

    public function store(Request $request)
    {
        $this->authorize('create_scores');
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'dept_id' => 'required|exists:departments,id',
                'period' => 'required|date_format:Y-m',
                'kpis' => 'required',
                'kpis.*.realisation' => 'required|numeric',
                'kpis.*.weight' => 'required|numeric',
                'kpis.*.kpi_target' => 'required|numeric',
                'kpis.*.is_max' => 'required|numeric',
            ]);

            // return $validatedData;

            // Convert period to the first day of the month
            $period = $validatedData['period'] . '-01';

            foreach ($validatedData['kpis'] as $kpiId => $kpiData) {
                $score = ($kpiData['realisation'] / $kpiData['kpi_target']) * $kpiData['weight'];

                // If is_max false then its a cost
                if (!$kpiData['is_max']) {
                    $score = -$score;
                }

                ScoreKpi::create([
                    'kpi_id' => $kpiId,
                    'realisation' => $kpiData['realisation'],
                    'score' => $score,
                    // 'score' => ($kpiData['realisation']/$kpiData['kpi_target'])*$kpiData['weight'],
                    'dept_id' => $validatedData['dept_id'],
                    'period' => $period
                ]);
            }

            DB::commit();

            return redirect()->route('admin.scoreKpi.index')->with('success', 'KPIs stored successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('danger', $e->getMessage())->withInput();
        }


        // foreach ($validatedData['kpi_desc'] as $index => $kpiDesc) {
        //     $kpiData = [
        //         'kpi_desc' => $kpiDesc,
        //         'dept_id' => $validatedData['dept_id'][$index],
        //         'weight' => $validatedData['weight'][$index],
        //         'kpi_target' => $validatedData['kpi_target'][$index],
        //         'unit' => $validatedData['unit'][$index],
        //         'is_max' => $validatedData['is_max'][$index],
        //     ];
            
        //     $this->kpiRepo->store($kpiData);
        // }
    }

}

<?php

namespace App\Repositories;


use App\Models\Post;
use App\Models\ScoreKpi;
use Illuminate\Support\Facades\DB;

class scoreKpiRepository
{

    public function getAllScoreKpis($filterParameters,$with=[],$select=['*'])
    {
        return ScoreKpi::select($select)
            ->with($with)
            ->when(isset($filterParameters['department']), function ($query) use ($filterParameters) {
                $query->whereHas('department',function($subQuery) use ($filterParameters){
                    $subQuery->where('id', $filterParameters['department']);
                });
            })
            ->groupBy('dept_id', 'period')
            ->orderBy('dept_id')
            ->orderBy('period')
            ->latest()
            ->paginate(ScoreKpi::RECORDS_PER_PAGE);
    }

    public function getDetailedScore($with){
        return ScoreKpi::with($with)
        ->get()
        ->groupBy(function ($item) {
            return $item->dept_id . '-' . $item->period;
        });
    }

    public function calculate(){
        $records = DB::table('score_kpis')
            ->select('dept_id', 'period', 'score')
            ->get();

        // Initialize an array to store the computed results
        $results = [];

        foreach ($records as $record) {
            // Create a unique key for each department and period
            $key = $record->dept_id . '-' . $record->period;

            // Initialize the key in the results array if it doesn't exist
            if (!isset($results[$key])) {
                $results[$key] = [
                    'dept_id' => $record->dept_id,
                    'period' => $record->period,
                    'total_score' => 0,
                ];
            }

            // Sum the scores manually
            $results[$key]['total_score'] += $record->score;
        }

        // Convert the results to a collection or array as needed
        return $results = collect($results)->values();
    }

    // public function getAllKpisWithId($)

    public function store(array $validatedData)
    {
        return ScoreKpi::create($validatedData)->fresh();
    }


    public function getKpiById($id)
    {
        return ScoreKpi::where('id',$id)->first();
    }

    public function delete($kpiDetail)
    {
        return $kpiDetail->delete();
    }

    public function update($kpiDetail,$validatedData)
    {
        return $kpiDetail->update($validatedData);
    }


    public function getAllActiveScoreByDepartmentId($deptId,$with=[],$select=['*'])
    {
        return ScoreKpi::with($with)
            ->select($select)
            ->where('dept_id',$deptId)
            ->get();
    }

    // public function getAllKpisParam(array $with=[], array $select=['weight','target'])
    // {
    //     return Kpi::with($with)
    //         ->select($select)
    //         ->where('is_active',1)
    //         ->get();
    // }
}

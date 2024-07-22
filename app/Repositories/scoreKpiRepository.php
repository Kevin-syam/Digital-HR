<?php

namespace App\Repositories;


use App\Models\Post;
use App\Models\ScoreKpi;

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
            ->orderBy('dept_id')
            ->latest()
            ->paginate(ScoreKpi::RECORDS_PER_PAGE);
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

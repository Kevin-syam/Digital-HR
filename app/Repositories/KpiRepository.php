<?php

namespace App\Repositories;


use App\Models\Post;
use App\Models\Kpi;

class KpiRepository
{

    public function getAllKpis($filterParameters,$with=[],$select=['*'])
    {
        return Kpi::select($select)
            ->with($with)
            ->when(isset($filterParameters['department']), function ($query) use ($filterParameters) {
                $query->whereHas('department',function($subQuery) use ($filterParameters){
                    $subQuery->where('id', $filterParameters['department']);
                });
            })
            ->groupBy('dept_id')
            ->orderBy('dept_id')
            ->latest()
            ->paginate(Kpi::RECORDS_PER_PAGE);
    }

    // public function getAllKpisWithId($)

    public function store(array $validatedData)
    {
        return Kpi::create($validatedData)->fresh();
    }


    public function getKpiById($id)
    {
        return Kpi::where('id',$id)->first();
    }

    public function getKpiByDeptId($id)
    {
        return Kpi::where('dept_id',$id)->get();
    }

    public function delete($kpiDetail)
    {
        return $kpiDetail->delete();
    }

    public function deleteMulti($kpiDetails)
    {
        return Kpi::whereIn('id', $kpiDetails->pluck('id'))->delete(); // Delete all KPIs by their IDs
    }


    public function update($kpiDetail,$validatedData)
    {
        return $kpiDetail->update($validatedData);
    }


    public function getAllActivePostsByDepartmentId($deptId,$with=[],$select=['*'])
    {
        return Kpi::with($with)
            ->select($select)
            ->where('dept_id',$deptId)
            ->get();
    }

    public function getDetailedKpis($with){
        return Kpi::with($with)
        ->get()
        ->groupBy(function ($item) {
            return $item->dept_id;
        });
    }

    // public function getAllKpisParam(array $with=[], array $select=['weight','target'])
    // {
    //     return Kpi::with($with)
    //         ->select($select)
    //         ->where('is_active',1)
    //         ->get();
    // }
}

<?php

namespace App\Repositories;

use App\Models\Tada;

class TadaRepository
{
    public function getAllTadaPaginated($filterParameters,$select,$with)
    {
       return Tada::query()->select($select)->with($with)
           ->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
               $query->where('status', $filterParameters['status']);
           })
           ->when(isset($filterParameters['employee']), function ($query) use ($filterParameters) {
               $query->whereHas('employeeDetail',function($subQuery) use ($filterParameters){
                   $subQuery->where('name', 'like', '%' . $filterParameters['employee'] . '%');
               });
           })
           ->latest()
           ->paginate(Tada::RECORDS_PER_PAGE);
    }

    public function getAllActiveTadaDetail($select,$with)
    {
        return Tada::select($select)->with($with)
            ->where('is_active',1)
            ->get();
    }

    public function findTadaDetailById($id,$select,$with)
    {
        return Tada::select($select)->with($with)
            ->where('id',$id)
            ->first();
    }

    public function findEmployeeTadaDetailByTadaId($id,$select,$with)
    {
        return Tada::select($select)->with($with)
            ->where('employee_id',getAuthUserCode())
            ->where('id',$id)
            ->first();
    }

    public function getEmployeeTadaDetailLists($employeeId,$select,$with)
    {
        return Tada::select($select)->with($with)
            ->where('employee_id',$employeeId)
            ->where('is_active',1)
            ->get();
    }

    public function store($validatedData)
    {
        return Tada::create($validatedData)->fresh();
    }

    public function update($tadaDetail, $validatedData)
    {
        return $tadaDetail->update($validatedData);
    }

    public function delete($tadaDetail)
    {
        return $tadaDetail->delete();
    }

    public function toggleStatus($detail)
    {
        return $detail->update([
           'is_active' => !$detail->is_active
        ]);
    }

    public function createManyAttachment(Tada $tadaDetail,$attachments)
    {
        return $tadaDetail->attachments()->createMany($attachments);
    }

    public function deleteTadaAttachments(Tada $tadaDetail)
    {
        return $tadaDetail->attachments()->delete();
    }

    public function changeTadaStatus($tadaDetail, $validatedData)
    {
        return $tadaDetail->update([
           'status' => $validatedData['status'],
           'remark' => $validatedData['remark'],
           'verified_by' => getAuthUserCode()
        ]);
    }

}

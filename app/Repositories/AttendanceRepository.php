<?php

namespace App\Repositories;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceRepository
{

    public function getAllCompanyEmployeeAttendanceDetailOfTheDay($filterParameter)
    {
        return User::select(
                            'attendances.id AS attendance_id',
                            'users.id AS user_id',
                            'users.name AS user_name',
                            'users.company_id AS company_id',
                            'users.branch_id AS branch_id',
                            'companies.name AS company_name',
                            'attendances.attendance_date',
                            'attendances.attendance_status',
                            'attendances.check_in_at',
                            'attendances.check_out_at',
                            'attendances.check_in_latitude',
                            'attendances.check_out_latitude',
                            'attendances.check_in_longitude',
                            'attendances.check_out_longitude',
                            'attendances.edit_remark',
                            'attendances.created_by',
                            'attendances.updated_by',
                    )->leftJoin('attendances', function ($join) use ($filterParameter) {
                            $join->on('users.id','=', 'attendances.user_id')
                                ->where('attendances.attendance_date','=',$filterParameter['attendance_date']);
                        })
                    ->join('companies', 'users.company_id', '=', 'companies.id')
                    ->join('branches','users.branch_id','=', 'branches.id')
                    ->when(isset($filterParameter['branch_id']), function($query) use ($filterParameter){
                        $query->where('users.branch_id',$filterParameter['branch_id']);
                    })
                    ->get();

    }

    public function getEmployeeAttendanceDetailOfTheMonth($filterParameters,$select=['*'],$with=[])
    {
        $attendanceList = Attendance::with($with)
            ->select($select)
            ->where('user_id',$filterParameters['user_id']);
            if (isset($filterParameters['start_date'])) {
                $attendanceList->whereBetween('attendance_date', [$filterParameters['start_date'], $filterParameters['end_date']]);
            } else {
                $attendanceList
                    ->whereMonth('attendance_date','=',$filterParameters['month'])
                    ->whereYear('attendance_date','=',$filterParameters['year']);
            }
//            dd($attendanceList->get());
        return $attendanceList->get();
    }

    public function findEmployeeTodayCheckInDetail($userId,$select=['*'])
    {
        return Attendance::select($select)
            ->where('user_id',$userId)
            ->where('attendance_date',Carbon::now()->format('Y-m-d'))
            ->first();
    }

    public function findAttendanceDetailById($id,$select=['*'])
    {
        return Attendance::where('id',$id)->first();
    }

    public function updateAttendanceStatus($attendanceDetail)
    {
        return $attendanceDetail->update([
            'attendance_status' => !$attendanceDetail->attendance_status
        ]);
    }

   public function delete(Attendance $attendanceDetail)
   {
       return $attendanceDetail->delete();
   }

    public function storeAttendanceDetail($validatedData)
    {
        return Attendance::create($validatedData)->fresh();
    }

    public function updateAttendanceDetail($attendanceDetail,$validatedData)
    {
         $attendanceDetail->update($validatedData);
         return $attendanceDetail;
    }
}

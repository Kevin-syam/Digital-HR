<?php

namespace App\Resources\Dashboard;


use App\Resources\Attendance\TodayAttendanceResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeTodayAttendance extends JsonResource
{
    public function toArray($request)
    {
        return $this->employeeTodayAttendance ? new TodayAttendanceResource($this->employeeTodayAttendance) : [
            'check_in_at' => '-',
            'check_out_at' =>  '-',
            'productive_time_in_min' => 0
        ];
    }
}















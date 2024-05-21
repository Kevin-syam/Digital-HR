<?php

namespace App\Resources\Attendance;


use App\Helpers\AttendanceHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlyEmployeeAttendanceResource extends JsonResource
{
    public function toArray($request)
    {
        $data['user_detail'] = [
            'user_id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
        if ($this->employeeTodayAttendance) {
            $data['employee_today_attendance'] = [
                'check_in_at' => $this->employeeTodayAttendance->check_in_at ? AttendanceHelper::changeTimeFormatForAttendanceView($this->employeeTodayAttendance->check_in_at) : '-',
                'check_out_at' => $this->employeeTodayAttendance->check_out_at ? AttendanceHelper::changeTimeFormatForAttendanceView($this->employeeTodayAttendance->check_out_at) : '-',
            ];
        } else {
            $data['employee_today_attendance'] = [
                'check_in_at' => '-',
                'check_out_at' =>  '-',
            ];
        }
        if ($this->employeeAttendance->count() > 0) {
            $data['employee_attendance'] = new EmployeeAttendanceDetailCollection($this->employeeAttendance);
        } else {
            $data['employee_attendance'] = [];
        }
        return $data;
    }
}















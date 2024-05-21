<?php

namespace App\Resources\Attendance;

use App\Helpers\AppHelper;
use App\Helpers\AttendanceHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'attendance_date' => AppHelper::dateInDDMMFormat($this->attendance_date, false),
            'attendance_date_nepali' => AppHelper::dateInDDMMFormat($this->attendance_date),
            'week_day' => AttendanceHelper::getWeekDayInShortForm($this->attendance_date),
            'check_in' => $this->check_in_at ? AttendanceHelper::changeTimeFormatForAttendanceView($this->check_in_at) : '-',
            'check_out' => $this->check_out_at ? AttendanceHelper::changeTimeFormatForAttendanceView($this->check_out_at) : '-',
        ];
    }
}














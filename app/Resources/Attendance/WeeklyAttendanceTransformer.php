<?php

namespace App\Resources\Attendance;

use App\Helpers\AttendanceHelper;
use Carbon\Carbon;
use Exception;

class WeeklyAttendanceTransformer
{
    private $weeklyAttendanceReport;

    public function __construct($weeklyAttendanceReport)
    {
        $this->weeklyAttendanceReport = $weeklyAttendanceReport;
    }

    public function transform()
    {
        return $this->handle($this->weeklyAttendanceReport);
    }

    private function handle($weeklyAttendanceReport)
    {
        $weeklyReport = [];
        $attendanceDetail = [];
        foreach($weeklyAttendanceReport as $key => $value){
            $attendanceDetail[date('w', strtotime($value['attendance_date']))] = $value;
        }
        for($i=0; $i<7; $i++){
            if(isset($attendanceDetail[$i])){
                if(!$attendanceDetail[$i]['check_out_at'] && Carbon::parse($attendanceDetail[$i]['attendance_date']) != Carbon::today()){
                    $time =  null;
                }else{
                    $time =  $attendanceDetail[$i]['check_out_at'] ?? Carbon::now();
                }
                $weeklyReport[] = [
                    'week_day' => AttendanceHelper::getWeekDayFromDate($attendanceDetail[$i]['attendance_date']),
                    'week_day_in_number' => date('w', strtotime($attendanceDetail[$i]['attendance_date'])),
                    'attendance_date' => $attendanceDetail[$i]['attendance_date'],
                    'check_in' =>  AttendanceHelper::changeTimeFormatForAttendanceView($attendanceDetail[$i]['check_in_at']) ?? '-',
                    'check_out' => ($attendanceDetail[$i]['check_out_at']) ? AttendanceHelper::changeTimeFormatForAttendanceView($attendanceDetail[$i]['check_out_at']) : '-',
                    'productive_time_in_min' => $time ? \Carbon\Carbon::createFromFormat('H:i:s', $attendanceDetail[$i]['check_in_at'])->diffInMinutes($time) : 0,
                ];
            }else{
                $weeklyReport[] = null;
            }
        }
        return $weeklyReport;
    }
}



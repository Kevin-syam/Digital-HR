<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceExport implements FromView, ShouldAutoSize
{
    protected $attendanceRecord;
    protected $userDetail;

    function __construct($attendanceRecord,$userDetail)
    {
        $this->attendanceRecord = $attendanceRecord;
        $this->userDetail = $userDetail;
    }

    public function view(): View
    {
        return view('admin.attendance.export.attendance-export', [
            'attendanceRecordDetail' => $this->attendanceRecord,
            'employeeDetail' => $this->userDetail,
        ]);
    }

}

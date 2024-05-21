<?php

namespace App\Helpers;

use App\Exports\DatabaseData\LeaveMaster;
use App\Models\Company;
use App\Models\Holiday;
use App\Models\LeaveRequestMaster;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class AttendanceHelper
{
    const WEEK_DAY_IN_NEPALI = [
        '0' => array(
            'en' => 'Sunday',
            'en_short' => 'Sun',
            'np' => 'आइतबार',
            'np_short' => 'आइत',
        ),
        '1' => array(
            'en' => 'Monday',
            'en_short' => 'Mon',
            'np' => 'सोमबार',
            'np_short' => 'सोम',
        ),
        '2' => array(
            'en' => 'Tuesday',
            'en_short' => 'Tue',
            'np' => 'मंगलबार',
            'np_short' => 'मंगल',
        ),
        '3' => array(
            'en' => 'Wednesday',
            'en_short' => 'Wed',
            'np' => 'बुधबार',
            'np_short' => 'बुध',
        ),
        '4' => array(
            'en' => 'Thursday',
            'en_short' => 'Thur',
            'np' => 'बिहिबार',
            'np_short' => 'बिहि',
        ),
        '5' => array(
            'en' => 'Friday',
            'en_short' => 'Fri',
            'np' => 'शुक्रबार',
            'np_short' => 'शुक्र',
        ),
        '6' => array(
            'en' => 'Saturday',
            'en_short' => 'Sat',
            'np' => 'शनिबार',
            'np_short' => 'शनि',
        ),
    ];

    const WEEK_DAY = [
        'sunday' => 0,
        'saturday' => 6,
        'monday' => 2,
    ];


    public static function getStartOfWeekDate($currentDate): mixed
    {
        return $currentDate->startOfWeek(self::WEEK_DAY['sunday'])->format('Y-m-d');
    }

    public static function getEndOfWeekDate($currentDate): mixed
    {
        return $currentDate->endOfWeek(self::WEEK_DAY['saturday'])->format('Y-m-d');
    }

    public static function getWeekDayFromDate($date): string
    {
        $week = date('w', strtotime($date));
        return self::WEEK_DAY_IN_NEPALI[$week]['en'];
    }

    public static function getWeekDayInShortForm($date): string
    {
        $week = date('w', strtotime($date));
        return self::WEEK_DAY_IN_NEPALI[$week]['en_short'];
    }

    public static function changeTimeFormatForAttendanceView($time): string
    {
        $appTimeSetting = AppHelper::check24HoursTimeAppSetting();
        if ($appTimeSetting) {
            return date('H:i:s', strtotime($time));
        }
        return date('h:i A', strtotime($time));
    }

    public static function changeTimeFormatForAttendanceAdminView($time): string
    {
        $appTimeSetting = AppHelper::check24HoursTimeAppSetting();
        if ($appTimeSetting) {
            return date('H:i:s', strtotime($time));
        }
        return date('h:i:s A', strtotime($time));
    }

    public static function getWorkedHourInHourAndMinute($checkInTime, $checkOutTime): string
    {
        $workedTimeInMinute = Carbon::createFromFormat('H:i:s', $checkInTime)->diffInMinutes($checkOutTime);
        return intdiv($workedTimeInMinute, 60) . ' hr ' . ($workedTimeInMinute) % 60 . ' min';
    }

    public static function isHolidayOrWeekendOnCurrentDate(): bool
    {
        $date = Carbon::today()->format('Y-m-d');
        $weekDay = self::getWeekDayInNumber($date);
        $holiday = Holiday::whereDate('event_date', $date)->count();
        if ($holiday == 0) {
            $weekend = Company::whereJsonContains('weekend', $weekDay)->count();
            if ($weekend > 0) {
                return false;
            }
        }
        return true;
    }

    public static function getWeekDayInNumber($date): string
    {
        return date('w', strtotime($date));
    }

    public static function getTotalNumberOfDaysInSpecificMonth($month, $year): int
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    public static function getEmployeeWorkedTimeInHourAndMinute($attendanceDetail): string
    {
        $productiveTimeInMin = Carbon::createFromFormat('H:i:s', $attendanceDetail['check_out_at'])->diffInMinutes($attendanceDetail['check_in_at']);
        return floor($productiveTimeInMin / 60) . ' hrs and ' . ($productiveTimeInMin - floor($productiveTimeInMin / 60) * 60) . ' min(s)';
    }

    public static function formattedAttendanceDate($date, $changeEngToNep=true): string
    {
        if(AppHelper::ifDateInBsEnabled() && $changeEngToNep){
            return  AppHelper::getFormattedNepaliDate($date);
        }
        return date('d M Y',strtotime($date));
    }

    public static function getHolidayOrLeaveDetail($date,$userId)
    {
        $leaveStatus = [
           'pending' => 'P',
           'rejected' => 'R',
           'approved' => 'A',
        ];
        if(AppHelper::ifDateInBsEnabled()){
            $date = AppHelper::dateInYmdFormatNepToEng($date);
        }
        if(Carbon::parse($date) < Carbon::today()){
            $holidayDetail = Holiday::whereDate('event_date',$date)->first();
            if($holidayDetail){
                return 'Holiday ('.(ucfirst($holidayDetail->event)). ')';
            }
            $leaveDetail = LeaveRequestMaster::where('requested_by',$userId)
                ->whereDate('leave_from','>=',$date)
                ->whereDate('leave_to', '<=', $date)
                ->first();
            if($leaveDetail){
                return 'Leave ('.($leaveStatus[$leaveDetail['status']]).')';
            }
            $weekday = date('w', strtotime($date));
            $companyWeekend = Company::whereJsonContains('weekend',$weekday)->exists();
            if($companyWeekend){
                return 'Weekend';
            }
            return 'Absent';
        }


    }

}

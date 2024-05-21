<?php

namespace App\Helpers;

use App\Helpers\SMPush\SMPushHelper;
use App\Models\AppSetting;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\GeneralSetting;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AppHelper
{
    const IS_ACTIVE = 1;

    const MONTHS = [
        '1' => array(
            'en' => 'Jan',
            'np' => 'Baishakh',
        ),
        '2' => array(
            'en' => 'Feb',
            'np' => 'Jestha',
        ),
        '3' => array(
            'en' => 'Mar',
            'np' => 'Asar',
        ),
        '4' => array(
            'en' => 'Apr',
            'np' => 'Shrawan',
        ),
        '5' => array(
            'en' => 'May',
            'np' => 'Bhadra',
        ),
        '6' => array(
            'en' => 'Jun',
            'np' => 'Ashwin',
        ),
        '7' => array(
            'en' => 'Jul',
            'np' => 'kartik',
        ),
        '8' => array(
            'en' => 'Aug',
            'np' => 'Mangsir',
        ),
        '9' => array(
            'en' => 'Sept',
            'np' => 'Poush',
        ),
        '10' => array(
            'en' => 'Oct',
            'np' => 'Magh',
        ),
        '11' => array(
            'en' => 'Nov',
            'np' => 'Falgun',
        ),
        '12' => array(
            'en' => 'Dec',
            'np' => 'Chaitra',
        ),

    ];

    public static function getAuthUserCompanyId(): int
    {
        $user = auth()->user();
        if (!$user) {
            throw new Exception('unauthenticated', 401);
        }
        $companyId = optional($user)->company_id;
        if (!$companyId) {
            throw new Exception('User Company Id not found', 401);
        }
        return $companyId;
    }

    public static function getCompanyLogo()
    {
        $company = Company::select('logo')->first();
        return optional($company)->logo;
    }

    public static function getAuthUserRole()
    {
        $user = auth()->user();
        if (!$user) {
            throw new Exception('unauthenticated', 401);
        }
        return $user->role->name;
    }

    public static function findAdminUserAuthId()
    {
        $user = User::whereHas('role', function ($query) {
            $query->where('name', 'admin');
        })->first();
        if (!$user) {
            throw new Exception('Admin User Not Found', 400);
        }
        return $user->id;
    }

    public static function getAuthUserBranchId()
    {
        $user = auth()->user();
        if(!$user){
            throw new Exception('unauthenticated',401);
        }
        $branchId = optional($user)->branch_id;
        if (!$branchId) {
            throw new Exception('User Branch Id Not Found',400);
        }
        return $branchId;
    }

    public static function getFirebaseServerKey(): mixed
    {
        return GeneralSetting::where('key', 'firebase_key')->value('value') ?: '';
    }

    public static function sendErrorResponse($message, $code = 500, array $errorFields = null): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $message,
            'status_code' => $code,
        ];
        if (!is_null($errorFields)) {
            $response['data'] = $errorFields;
        }
        if ($code < 200 || !is_numeric($code) || $code > 599) {
            $code = 500;
            $response['code'] = $code;
        }
        return response()->json($response, $code);
    }

    public static function sendSuccessResponse($message, $data = null, $headers = [], $options = 0): JsonResponse
    {
        $response = [
            'status' => true,
            'message' => $message,
            'status_code' => 200,

        ];
        if (!is_null($data)) {
            $response['data'] = $data;
        }
        return response()->json($response, 200, $headers, $options);
    }

    public static function getProgressBarStyle($progressPercent): string
    {
        $width = 'width: ' . $progressPercent . '%;';

        if ($progressPercent >= 0 && $progressPercent < 26) {
            $color = 'background-color:#C1E1C1';
        } elseif ($progressPercent >= 26 && $progressPercent < 51) {
            $color = 'background-color:#C9CC3F';
        } elseif ($progressPercent >= 51 && $progressPercent < 76) {
            $color = 'background-color: #93C572';
        } else {
            $color = 'background-color:#3cb116';
        }
        return $width . $color;
    }

    public static function convertLeaveDateFormat($dateTime, $changeEngToNep = true): string
    {
        if (self::check24HoursTimeAppSetting()) {
            if (self::ifDateInBsEnabled() && $changeEngToNep) {
                $date = self::getDayMonthYearFromDate($dateTime);
                $dateInBs = (new DateConverter())->engToNep($date['year'], $date['month'], $date['day']);
                $time = date('H:i', strtotime($dateTime));
                return $dateInBs['date'] . ' ' . $dateInBs['nmonth'] . ' ' . $time;
            }
            return date('M d H:i ', strtotime($dateTime));
        } else {
            if (self::ifDateInBsEnabled() && $changeEngToNep) {
                $date = self::getDayMonthYearFromDate($dateTime);
                $dateInBs = (new DateConverter())->engToNep($date['year'], $date['month'], $date['day']);
                $time = date('h:i A', strtotime($dateTime));
                return $dateInBs['date'] . ' ' . $dateInBs['nmonth'] . ' ' . $time;
            }
            return date('M d h:i A', strtotime($dateTime));
        }
    }

    public static function check24HoursTimeAppSetting(): bool
    {
        $slug = '24-hour-format';
        return AppSetting::where('slug', $slug)->where('status', 1)->exists();
    }

    public static function ifDateInBsEnabled(): bool
    {
        $slug = 'bs';
        return AppSetting::where('slug', $slug)->where('status', 1)->exists();
    }

    public static function getDayMonthYearFromDate($date): array
    {
        return [
            'year' => date('Y', strtotime($date)),
            'month' => date('n', strtotime($date)),
            'day' => date('d', strtotime($date)),
        ];
    }

    public static function getCurrentDateInYmdFormat(): string
    {
        return Carbon::now()->format('Y-m-d');
    }

    public static function getCurrentYear(): string
    {
        return Carbon::now()->format('Y');
    }

    public static function getFormattedNepaliDate($date): string
    {
        $data = self::getDayMonthYearFromDate($date);
        return $data['day'] . ' ' . self::MONTHS[$data['month']]['np'] . ' ' . $data['year'];
    }

    public static function dateInYmdFormatEngToNep($date): string
    {
        $date = self::getDayMonthYearFromDate($date);
        $dateInAd = (new DateConverter())->engToNep($date['year'], $date['month'], $date['day']);
        return $dateInAd['year'] . '-' . $dateInAd['month'] . '-' . $dateInAd['date'];
    }

    public static function dateInDDMMFormat($date, $dateEngToNep = true): string
    {
        if ($dateEngToNep) {
            $date = explode(' ', self::formatDateForView($date));
            return $date[0] . ' ' . $date[1];
        }
        return date('d M', strtotime($date));
    }

    public static function formatDateForView($date, $changeEngToNep = true): string
    {
        if (self::ifDateInBsEnabled() && $changeEngToNep) {
            $date = self::getDayMonthYearFromDate($date);
            $dateInBs = (new DateConverter())->engToNep($date['year'], $date['month'], $date['day']);
            return $dateInBs['date'] . ' ' . $dateInBs['nmonth'] . ' ' . $dateInBs['year'];
        }
        return date('d M Y', strtotime($date));
    }

    public static function getTotalDaysInNepaliMonth($year, $month): int
    {
        return (new DateConverter())->getTotalDaysInMonth($year, $month);
    }

    public static function yearDetailToFilterData()
    {
        $dateArray = [
            'start_date' => null,
            'end_date' => null,
            'year' => Carbon::now()->format('Y-m-d'),
        ];
        if (self::ifDateInBsEnabled()) {
            $nepaliDate = self::getCurrentNepaliYearMonth();
            $dateInAD = self::findAdDatesFromNepaliMonthAndYear($nepaliDate['year']);
            $dateArray['start_date'] = $dateInAD['start_date'];
            $dateArray['end_date'] = $dateInAD['end_date'];
        }
        return $dateArray;
    }

    public static function getCurrentNepaliYearMonth(): array
    {
        return (new DateConverter())->getCurrentMonthAndYearInNepali();
    }

    public static function findAdDatesFromNepaliMonthAndYear($year, $month = ''): array
    {
        if (!empty($month)) {
            return (new DateConverter())->getStartAndEndDateFromGivenNepaliMonth($year, $month);
        }
        return (new DateConverter())->getStartAndEndDateOfYearFromGivenNepaliYear($year);
    }

    public static function getCurrentDateInBS(): string
    {
        return (new DateConverter())->getTodayDateInBS();
    }

    public static function weekDay($date): string
    {
        if (self::ifDateInBsEnabled()) {
            $date = self::dateInYmdFormatNepToEng($date);
        }
        return date('D', strtotime($date));
    }

    public static function dateInYmdFormatNepToEng($date): string
    {
        $date = self::getDayMonthYearFromDate($date);
        $dateInAd = (new DateConverter())->nepToEng($date['year'], $date['month'], $date['day']);
        return $dateInAd['year'] . '-' . $dateInAd['month'] . '-' . $dateInAd['date'];
    }

    public static function dateInYmdFormatNepToEngForProject($date): string
    {
        $explodedData = explode('-', $date);
        $date = [
                'year' => $explodedData[0],
                'month' => $explodedData[1],
                'day' => $explodedData[2]
            ];
        $dateInAd = (new DateConverter())->nepToEng($date['year'], $date['month'], $date['day']);
        return $dateInAd['year'] . '-' . $dateInAd['month'] . '-' . $dateInAd['date'];
    }

    public static function getFormattedAdDateToBs($englishDate): string
    {
        $date = self::getDayMonthYearFromDate($englishDate);
        $dateInBs = (new DateConverter())->engToNep($date['year'], $date['month'], $date['day']);
        return $dateInBs['date'] . ' ' . $dateInBs['nmonth'] . ' ' . $dateInBs['year'];
    }

    public static function getBsNxtYearEndDateInAd()
    {
        $addYear = 1;
        $nepaliDate = self::getCurrentNepaliYearMonth();
        $dateInAD = self::findAdDatesFromNepaliMonthAndYear($nepaliDate['year'] + $addYear);
        return $dateInAD['end_date'];
    }

    public static function getBackendLoginAuthorizedRole()
    {
        if (Cache::has('role')) {
            return Cache::get('role');
        } else {
            $roles = [];
            $backendAuthorizedLoginRole = Role::select('slug')->where('backend_login_authorize', 1)->get();
            foreach ($backendAuthorizedLoginRole as $key => $value) {
                $roles[] = $value->slug;
            }
            Cache::forever('role', $roles);
        }
        return $roles;
    }


    public static function getTheme()
    {
//        if (Cache::has('theme')){
//            return Cache::get('theme');
//        } else {
//            $getTheme = AppSetting::select('status')->where('slug','dark-theme')->first();
//            $theme = $getTheme->status ? 'light' : 'dark' ;
//            Cache::forever('theme', $theme);
//        }
        return $theme = 'light';
    }

    public static function employeeTodayAttendanceDetail()
    {
        $today = Carbon::today();
        $userId = auth()->id();
        return Attendance::select(['attendance_date', 'check_in_at', 'check_out_at'])
            ->where('user_id', $userId)
            ->whereDate('attendance_date', $today)
            ->first();
    }

    public static function getDaysToFindDatesForShiftNotification()
    {
        $key = 'attendance_notify';
        return GeneralSetting::where('key',$key)->value('value') ?? 0;
    }

    public static function getAllRoleIdsWithGivenPermission($permissionKey)
    {
        return DB::table('permission_roles')
            ->leftJoin('permissions', function ($query) {
                $query->on('permission_roles.permission_id', '=', 'permissions.id');
            })
            ->Join('roles', function ($query) {
                $query->on('roles.id', '=', 'permission_roles.role_id')
                    ->where('roles.is_active',self::IS_ACTIVE);
            })
            ->where('permissions.permission_key', $permissionKey)
            ->pluck('permission_roles.role_id')
            ->toArray();
    }

    public static function sendNotificationToAuthorizedUser($title, $message, $permissionKey): void
    {
        $roleIds =  AppHelper::getAllRoleIdsWithGivenPermission($permissionKey);
        if(!empty($roleIds)){
            SMPushHelper::sendNotificationToAuthorizedUsers($title, $message,$roleIds);
        }
    }

}

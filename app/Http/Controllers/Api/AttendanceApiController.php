<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Helpers\AttendanceHelper;
use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Requests\Attendance\AttendanceCheckInRequest;
use App\Requests\Attendance\AttendanceCheckOutRequest;
use App\Resources\Attendance\MonthlyEmployeeAttendanceResource;
use App\Resources\Attendance\TodayAttendanceResource;
use App\Services\Attendance\AttendanceService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AttendanceApiController extends Controller
{
    private AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function getEmployeeAllAttendanceDetailOfTheMonth(Request $request): JsonResponse
    {
        try{
            $filterParameter['month'] = $request->month ?? null;
            $filterParameter['user_id'] = getAuthUserCode();
            $with=['employeeTodayAttendance:user_id,check_in_at,check_out_at,attendance_date'];
            $select = [
                'users.id',
                'users.name',
                'users.email'
            ];
            $attendanceDetail = $this->attendanceService->getEmployeeAttendanceDetailOfTheMonthFromUserRepo($filterParameter,$select,$with);
            $data = new MonthlyEmployeeAttendanceResource($attendanceDetail);
            return AppHelper::sendSuccessResponse('Data Found',$data);
        }catch(Exception $exception){
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function employeeCheckIn(AttendanceCheckInRequest $request): JsonResponse
    {
        try {
            $this->authorize('check_in');
            $permissionKeyForNotification = 'employee_check_in';
            $userDetail = auth()->user();

            $checkHolidayAndWeekend = AttendanceHelper::isHolidayOrWeekendOnCurrentDate();
            if(!$checkHolidayAndWeekend){
                throw new Exception('Check In not allowed on holidays or on office Off Days',403);
            }

            $validatedData = $request->validated();
            $validatedData['user_id'] = $userDetail->id;
            $validatedData['company_id'] = $userDetail->company_id;

            $checkIn = $this->attendanceService->employeeCheckIn($validatedData);
            $data = new TodayAttendanceResource($checkIn);

            AppHelper::sendNotificationToAuthorizedUser(
                'Check In Notification',
                ucfirst(auth()->user()->name). ' checked in at '. AttendanceHelper::changeTimeFormatForAttendanceView($checkIn->check_in_at),
                $permissionKeyForNotification
            );
            return AppHelper::sendSuccessResponse('Check In Successful', $data);
        } catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function employeeCheckOut(AttendanceCheckOutRequest $request): JsonResponse
    {
        try {
            $userDetail = auth()->user();

            $this->authorize('check_out');
            $permissionKeyForNotification = 'employee_check_out';

            $checkHolidayAndWeekend = AttendanceHelper::isHolidayOrWeekendOnCurrentDate();
            if(!$checkHolidayAndWeekend){
                throw new Exception('Check out not allowed on holidays or on office Off Days',403);
            }

            $validatedData = $request->validated();
            $validatedData['user_id'] = $userDetail->id;
            $validatedData['company_id'] = $userDetail->company_id;

            $checkOut = $this->attendanceService->employeeCheckOut($validatedData);
            $data = new TodayAttendanceResource($checkOut);
            $workedTime = AttendanceHelper::getEmployeeWorkedTimeInHourAndMinute($checkOut);

            AppHelper::sendNotificationToAuthorizedUser(
                'Check Out Notification',
                ucfirst(auth()->user()->name). ' has checked out at '. AttendanceHelper::changeTimeFormatForAttendanceView($checkOut->check_out_at).' and has worked for '
                .$workedTime,
                $permissionKeyForNotification

            );
            return AppHelper::sendSuccessResponse('Check out Successful', $data);
        } catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}

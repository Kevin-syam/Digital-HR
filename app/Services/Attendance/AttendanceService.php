<?php

namespace App\Services\Attendance;

use App\Helpers\AppHelper;
use App\Helpers\AttendanceHelper;
use App\Models\Attendance;
use App\Models\User;
use App\Repositories\AppSettingRepository;
use App\Repositories\AttendanceRepository;
use App\Repositories\LeaveRepository;
use App\Repositories\RouterRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AttendanceService
{

    private AttendanceRepository $attendanceRepo;
    private UserRepository $userRepo;
    private RouterRepository $routerRepo;
    private AppSettingRepository $appSettingRepo;
    private LeaveRepository $leaveRepo;

    public function __construct(AttendanceRepository $attendanceRepo,
                                UserRepository       $userRepo,
                                RouterRepository     $routerRepo,
                                AppSettingRepository $appSettingRepo,
                                LeaveRepository $leaveRepo
    )
    {
        $this->attendanceRepo = $attendanceRepo;
        $this->userRepo = $userRepo;
        $this->routerRepo = $routerRepo;
        $this->appSettingRepo = $appSettingRepo;
        $this->leaveRepo = $leaveRepo;
    }

    /**
     * @param $filterParameter
     * @return mixed
     * @throws Exception
     */
    public function getAllCompanyEmployeeAttendanceDetailOfTheDay($filterParameter): mixed
    {
        try {
            if($filterParameter['date_in_bs']){
                $filterParameter['attendance_date'] = AppHelper::dateInYmdFormatNepToEng($filterParameter['attendance_date']);
            }
            return $this->attendanceRepo->getAllCompanyEmployeeAttendanceDetailOfTheDay($filterParameter);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $filterParameter
     * @param $select
     * @param $with
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getEmployeeAttendanceDetailOfTheMonth($filterParameter, $select = ['*'], $with = []): Collection|array
    {
        try {
            if($filterParameter['date_in_bs']){
                $days = AppHelper::getTotalDaysInNepaliMonth($filterParameter['year'],$filterParameter['month']);
                $dateInAD = AppHelper::findAdDatesFromNepaliMonthAndYear($filterParameter['year'], $filterParameter['month']);
                $filterParameter['start_date'] = $dateInAD['start_date'] ?? null;
                $filterParameter['end_date'] = $dateInAD['end_date'] ?? null;
            }else{
                $days = AttendanceHelper::getTotalNumberOfDaysInSpecificMonth($filterParameter['month'],$filterParameter['year']);
            }
            $employeeMonthlyAttendance = [];
            for($i=1; $i <= $days; ++$i) {
                $employeeMonthlyAttendance[] = [
                    'attendance_date' => Carbon::createFromDate($filterParameter['year'] , $filterParameter['month'], $i)->format('Y-m-d'),
                ];
            }
            $attendanceDetail = $this->attendanceRepo->getEmployeeAttendanceDetailOfTheMonth($filterParameter, $select);

            foreach ($attendanceDetail as $key => $value){
                if($filterParameter['date_in_bs']){
                    $attendanceDate = date('Y-m-d',strtotime(AppHelper::dateInYmdFormatEngToNep($value->attendance_date)));
                }else{
                    $attendanceDate = $value->attendance_date;
                }
                $getDay = date('d',strtotime($attendanceDate));
                $employeeMonthlyAttendance[$getDay-1] = [
                    'id' => $value->id,
                    'user_id' => $value->user_id,
                    'attendance_date' => $attendanceDate,
                    'check_in_at' => $value->check_in_at,
                    'check_out_at' => $value->check_out_at,
                    'check_in_latitude' => $value->check_in_latitude,
                    'check_out_latitude' => $value->check_out_latitude,
                    'check_in_longitude' => $value->check_in_longitude,
                    'check_out_longitude' => $value->check_out_longitude,
                    'attendance_status' => $value->attendance_status,
                    'note' => $value->note,
                    'edit_remark' => $value->edit_remark,
                    'created_by' => $value->created_by,
                    'created_at' => $value->created_at,
                    'updated_at' => $value->updated_at,
                ];
            }
            return $employeeMonthlyAttendance;
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function getEmployeeAttendanceDetailOfTheMonthFromUserRepo($filterParameter, $select = ['*'], $with = [])
    {
        try {
            if(AppHelper::ifDateInBsEnabled()){
                $nepaliDate = AppHelper::getCurrentNepaliYearMonth();
                $filterParameter['year'] = $nepaliDate['year'];
                $filterParameter['month'] = $filterParameter['month'] ?? $nepaliDate['month'];
                $dateInAD = AppHelper::findAdDatesFromNepaliMonthAndYear($filterParameter['year'], $filterParameter['month']);
                $filterParameter['start_date'] = $dateInAD['start_date'] ?? null;
                $filterParameter['end_date'] = $dateInAD['end_date'] ?? null;
            }else{
                $filterParameter['year'] = AppHelper::getCurrentYear();
                $filterParameter['month'] =  $filterParameter['month'] ?? now()->month;
            }
            return $this->userRepo->getEmployeeAttendanceDetailOfTheMonth($filterParameter, $select, $with);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function employeeCheckIn($validatedData)
    {
        try {
            $select = ['id', 'check_out_at'];
            $userTodayCheckInDetail = $this->attendanceRepo->findEmployeeTodayCheckInDetail($validatedData['user_id'],$select);
            if ($userTodayCheckInDetail) {
                throw new Exception('Sorry ! employee cannot check in twice a day.', 400);
            }
            $employeeLeaveDetail = $this->leaveRepo->findEmployeeApprovedLeaveForCurrentDate($validatedData,['id']);
            if($employeeLeaveDetail){
                throw new Exception('Cannot check in when leave request is Approved/Pending.',400);
            }

            $this->authorizeAttendance($validatedData['router_bssid']);

            $validatedData['attendance_date'] = Carbon::now()->format('Y-m-d');
            $validatedData['check_in_at'] = Carbon::now()->toTimeString();
            DB::beginTransaction();
                $attendance = $this->attendanceRepo->storeAttendanceDetail($validatedData);
                if ($attendance) {
                    $this->updateUserOnlineStatus($attendance->user_id,User::ONLINE);
                }
            DB::commit();
            return $attendance;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function employeeCheckOut($validatedData)
    {
        try {
            $select = ['id', 'check_out_at', 'check_in_at'];
            $userTodayCheckInDetail = $this->attendanceRepo->findEmployeeTodayCheckInDetail($validatedData['user_id'], $select);
            if (!$userTodayCheckInDetail) {
                throw new Exception('Not checked in yet', 400);
            }
            if ($userTodayCheckInDetail->check_out_at) {
                throw new Exception('Employee already checked out for today', 400);
            }
//            $employeeLeaveDetail = $this->leaveRepo->findEmployeeApprovedLeaveForCurrentDate($validatedData,['id']);
//            if($employeeLeaveDetail){
//                throw new Exception('Cannot check in when leave request is Approved/Pending.',400);
//            }
            $this->authorizeAttendance($validatedData['router_bssid']);
            $validatedData['check_out_at'] = Carbon::now()->toTimeString();
            DB::beginTransaction();
                $attendanceCheckOut = $this->attendanceRepo->updateAttendanceDetail($userTodayCheckInDetail,$validatedData);
                $this->updateUserOnlineStatus($validatedData['user_id'],User::OFFLINE);
            DB::commit();
            return $attendanceCheckOut;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateUserOnlineStatus($userId,$loginStatus)
    {
        try {
            $userDetail = $this->findUserDetailById($userId);
            if($userDetail->online_status == $loginStatus){
                return ;
            }
            DB::beginTransaction();
              $this->userRepo->updateUserOnlineStatus($userDetail,$loginStatus);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateUserOnlineStatusToOffline($userId)
    {
        try {
            $userDetail = $this->findUserDetailById($userId);
            DB::beginTransaction();
                $this->userRepo->updateUserOnlineStatus($userDetail,User::OFFLINE);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function findUserDetailById($userId,$select=['*'])
    {
        try {
            $employeeDetail = $this->userRepo->findUserDetailById($userId, $select);
            if (!$employeeDetail) {
                throw new Exception('User Detail Not found', 403);
            }
           return $employeeDetail;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function authorizeAttendance($routerBSSID)
    {
        try {
            $slug = 'override-bssid';
            $overrideBSSID = $this->appSettingRepo->findAppSettingDetailBySlug($slug);
            if($overrideBSSID && $overrideBSSID->status == 1){
                $select= ['workspace_type'];
                $employeeWorkSpace = $this->findUserDetailById(getAuthUserCode(), $select);
                if ($employeeWorkSpace->workspace_type == User::OFFICE) {
                    $checkEmployeeRouter = $this->routerRepo->findRouterDetailBSSID($routerBSSID);
                    if (!$checkEmployeeRouter) {
                        throw new Exception('Cannot take Attendance outside of workspace area');
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function changeAttendanceStatus($id)
    {
        try {
            $attendanceDetail = $this->attendanceRepo->findAttendanceDetailById($id);
            if (!$attendanceDetail) {
                throw new Exception('Attendance Detail Not Found', 403);
            }
            DB::beginTransaction();
            $this->attendanceRepo->updateAttendanceStatus($attendanceDetail);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function findAttendanceDetailById($id,$select=['*'])
    {
        try{
            $attendanceDetail = $this->attendanceRepo->findAttendanceDetailById($id);
            if(!$attendanceDetail){
                throw new Exception("Attendance Detail Not Found",404);
            }
            return $attendanceDetail;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function update($attendanceDetail,$validatedData)
    {
        try{
            return $this->attendanceRepo->updateAttendanceDetail($attendanceDetail,$validatedData);
        }catch(Exception $exception){
            return $exception;
        }
    }

    public function delete($id)
    {
        try{
            $attendanceDetail = $this->findAttendanceDetailById($id);
            DB::beginTransaction();
                 $this->attendanceRepo->delete($attendanceDetail);;
            DB::commit();
            return ;
        }catch(Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

}

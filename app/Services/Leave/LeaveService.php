<?php

namespace App\Services\Leave;

use App\Helpers\AppHelper;
use App\Repositories\LeaveRepository;
use App\Repositories\LeaveTypeRepository;
use Carbon\Carbon;
use Exception;
//use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveService
{
    private LeaveRepository $leaveRepo;
    private LeaveTypeRepository $leaveTypeRepo;

    public function __construct(LeaveRepository $leaveRepo, LeaveTypeRepository $leaveTypeRepo)
    {
        $this->leaveRepo = $leaveRepo;
        $this->leaveTypeRepo = $leaveTypeRepo;
    }

    public function getAllEmployeeLeaveRequests($filterParameters,$select=['*'],$with=[])
    {
        try{
            if(AppHelper::ifDateInBsEnabled()){
                $dateInAD = AppHelper::findAdDatesFromNepaliMonthAndYear($filterParameters['year'],$filterParameters['month']);
                $filterParameters['start_date'] = $dateInAD['start_date'];
                $filterParameters['end_date'] = $dateInAD['end_date'];
            }
            return $this->leaveRepo->getAllEmployeeLeaveRequest($filterParameters,$select,$with);
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function getAllLeaveRequestOfEmployee($filterParameters,$select=['*'],$with=[])
    {
        try{
            if(AppHelper::ifDateInBsEnabled()){
                $nepaliDate = AppHelper::getCurrentNepaliYearMonth();
                $month = isset($filterParameters['month']) ? $nepaliDate['month']: '';
                $dateInAD = AppHelper::findAdDatesFromNepaliMonthAndYear($nepaliDate['year'],$month);
                $filterParameters['start_date'] = $dateInAD['start_date'];
                $filterParameters['end_date'] = $dateInAD['end_date'];
            }
            return $this->leaveRepo->getAllLeaveRequestDetailOfEmployee($filterParameters,$select,$with);
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function findEmployeeLeaveRequestById($leaveRequestId,$select=['*'])
    {
        try{
           return $this->leaveRepo->findEmployeeLeaveRequestByEmployeeId($leaveRequestId,$select);
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function storeLeaveRequest($validatedData)
    {
        try{
            $leaveDate = $this->checkIfDateIsValidToRequestLeave($validatedData);
            $validatedData['no_of_days'] = ($leaveDate['to']->diffInDays($leaveDate['from']) + 1);
            $validatedData['company_id'] = AppHelper::getAuthUserCompanyId();
            $validatedData['leave_requested_date'] = Carbon::now()->format('Y-m-d h:i:s');
            $this->checkEmployeeLeaveRequest($validatedData);
            DB::beginTransaction();
                $this->leaveRepo->store($validatedData);
            DB::commit();
            return $validatedData;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    private function checkIfDateIsValidToRequestLeave($validatedData)
    {
        try{
            if(AppHelper::ifDateInBsEnabled()){
                $leave_from = \Carbon\Carbon::createFromFormat('Y-m-d', AppHelper::dateInYmdFormatEngToNep($validatedData['leave_from']));
                $leave_to = \Carbon\Carbon::createFromFormat('Y-m-d', AppHelper::dateInYmdFormatEngToNep($validatedData['leave_to']));
                if(date('Y',strtotime($leave_from)) != date('Y',strtotime($leave_to))){
                    throw new Exception('Leave to B.S year must be the same as the leave from B.S year.',403);
                }
            }else{
                $leave_from = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $validatedData['leave_from']);
                $leave_to = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $validatedData['leave_to']);
                if($leave_from->year != $leave_to->year){
                    throw new Exception('Leave to A.D year must be the same as the leave from A.D year.',403);
                }
            }
            return [
               'from' => $leave_from,
               'to' => $leave_to
            ];
        }catch(Exception $exception){
            throw $exception;
        }

    }


    /**
     * @param $validatedData
     * @return void
     * @throws Exception
     */
    private function checkEmployeeLeaveRequest($validatedData): void
    {
        try{
            $select= ['id','status'];
            $date['from_date'] = date('Y-m-d', strtotime($validatedData['leave_from']));
            $date['to_date'] = date('Y-m-d', strtotime($validatedData['leave_to']));
            $employeeLatestPendingLeaveRequest = $this->leaveRepo->getEmployeeLatestLeaveRequestBetweenFromAndToDate($select,$date);
            if($employeeLatestPendingLeaveRequest){
                throw new Exception('Leave request is already ' .$employeeLatestPendingLeaveRequest->status. ' for given date.',400);
            }
            $leaveType =  $this->leaveTypeRepo->findLeaveTypeDetailById($validatedData['leave_type_id']);
            $totalLeaveAllocated = $leaveType->leave_allocated;
            /**
             * unpaid leave are not allocated with any leave days .
             */
            if(is_null($totalLeaveAllocated)){
                return;
            }
            $leaveDetail = $this->leaveRepo->employeeTotalApprovedLeavesForGivenLeaveType($validatedData['leave_type_id']);
            $totalLeaveTakenTillNow = $leaveDetail->leaves ?? 0;
            if($totalLeaveAllocated < (int)$validatedData['no_of_days'] + $totalLeaveTakenTillNow  ){
                throw new Exception('Leave Request Days Exceeded by '
                    . (int)$validatedData['no_of_days'] + $totalLeaveTakenTillNow - $totalLeaveAllocated. ' days for '.$leaveType->name.'. Please try another type of leave',400);
            }
            return;
        }catch(\Exception $e){
            throw $e;
        }
    }


    /**
     * @param $validatedData
     * @param $leaveRequestId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
     * @throws Exception
     */
    public function updateLeaveRequestStatus($validatedData, $leaveRequestId)
    {
        try{
            $leaveRequestDetail = $this->findEmployeeLeaveRequestById($leaveRequestId);
            if(!$leaveRequestDetail){
                throw new \Exception('Leave request detail not found',404);
            }
            DB::beginTransaction();
                $this->leaveRepo->update($leaveRequestDetail,$validatedData);
            DB::commit();
            return $leaveRequestDetail;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function getLeaveCountDetailOfEmployeeOfTwoMonth()
    {
        try{
            $allLeaveRequest = $this->leaveRepo->getLeaveCountDetailOfEmployeeOfTwoMonth();
            if($allLeaveRequest){
                $leaveDates = [];
                foreach($allLeaveRequest as $key => $value){
                    $leaveRequestedDays = $value->no_of_days;
                    $i=0;
                    $fromDate = Carbon::parse( $value->leave_from)->format('Y-m-d');
                    for($i; $i<$leaveRequestedDays; $i++){
                        $leaveDates[] = date('Y-m-d', strtotime("+$i day", strtotime($fromDate)));
                    }
                }
                $leaveDetail = array_count_values($leaveDates);
                $dateWithNumberOfEmployeeOnLeave = [];
                foreach($leaveDetail as $key => $value){
                    $data = [];
                    $data['date']= $key;
                    $data['leave_count']= $value;
                    $dateWithNumberOfEmployeeOnLeave[] = $data;
                }
                return $dateWithNumberOfEmployeeOnLeave;
            }
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function getAllEmployeeLeaveDetailBySpecificDay($filterParameter)
    {
        try{
            return $this->leaveRepo->getAllEmployeeLeaveDetailBySpecificDay($filterParameter);
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function findLeaveRequestDetailByIdAndEmployeeId($leaveRequestId,$employeeId,$select=['*'])
    {
        try{
            $leaveRequestDetail = $this->leaveRepo->findEmployeeLeaveRequestDetailById($leaveRequestId,$employeeId,$select);
            if(!$leaveRequestDetail){
                throw new \Exception('Employee leave request detail not found',404);
            }
            return $leaveRequestDetail;
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function cancelLeaveRequest($validatedData,$leaveRequestDetail)
    {
        try{
            DB::beginTransaction();
                $this->leaveRepo->update($leaveRequestDetail,$validatedData);
            DB::commit();
            return $leaveRequestDetail;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }


}

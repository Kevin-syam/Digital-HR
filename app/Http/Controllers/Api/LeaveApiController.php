<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Models\LeaveRequestMaster;
use App\Requests\Leave\LeaveRequestStoreRequest;
use App\Resources\Leave\EmployeeLeaveDetailCollection;
use App\Resources\Leave\LeaveRequestCollection;
use App\Services\Leave\LeaveService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\Rule;

class LeaveApiController extends Controller
{
    private LeaveService $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    public function getAllLeaveRequestOfEmployee(Request $request): JsonResponse
    {
        try{
            $filterParameter = [
                'leave_type' => $request->leave_type ?? null,
                'status' => $request->status ?? null,
                'year' => $request->year ?? \Carbon\Carbon::now()->year,
                'month' => $request->month ?? null,
                'early_exit' => $request->early_exit ?? null,
                'user_id' => getAuthUserCode()
            ];
            $with = ['leaveType:id,name','leaveRequestedBy:id,name','leaveRequestUpdatedBy:id,name'];
            $select=['leave_requests_master.*'];
            $getAllLeaveRequests =  $this->leaveService->getAllLeaveRequestOfEmployee($filterParameter,$select,$with);
            $data = new LeaveRequestCollection($getAllLeaveRequests);
            return AppHelper::sendSuccessResponse('Data Found',$data);
        } catch (\Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function saveLeaveRequestDetail(LeaveRequestStoreRequest $request): JsonResponse
    {
        try {
            $this->authorize('leave_request_create');
            $permissionKeyForNotification = 'employee_leave_request';

            $validatedData = $request->validated();
            DB::beginTransaction();
              $leaveRequestDetail = $this->leaveService->storeLeaveRequest($validatedData);
            DB::commit();

            if($leaveRequestDetail) {
                AppHelper::sendNotificationToAuthorizedUser(
                        'Leave Request Notification',
                    ucfirst(auth()->user()->name). ' has requested ' .$leaveRequestDetail['no_of_days'] .
                        ' day(s) leave from ' .AppHelper::formatDateForView($leaveRequestDetail['leave_from']).
                        ' on ' .AppHelper::convertLeaveDateFormat($leaveRequestDetail['leave_requested_date']) . ' Reason: '.$validatedData['reasons'],
                    $permissionKeyForNotification
                );
            }
            return AppHelper::sendSuccessResponse('Leave request submitted successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getLeaveCountDetailOfEmployeeOfTwoMonth(): JsonResponse
    {
        try {
            $dateWithNumberOfEmployeeOnLeave = $this->leaveService->getLeaveCountDetailOfEmployeeOfTwoMonth();
            return AppHelper::sendSuccessResponse('Data Found',$dateWithNumberOfEmployeeOnLeave);
        } catch (\Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getAllEmployeeLeaveDetailBySpecificDay(Request $request): JsonResponse
    {
        try {
            $filterParameter['leave_date'] = $request->leave_date ?? Carbon::now()->format('Y-m-d');
            $leaveListDetail = $this->leaveService->getAllEmployeeLeaveDetailBySpecificDay($filterParameter);
            $leaveDetail = new EmployeeLeaveDetailCollection($leaveListDetail);
            return AppHelper::sendSuccessResponse('Data Found',$leaveDetail);
        } catch (\Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function cancelLeaveRequest($leaveRequestId)
    {
        try {
            $validatedData = [
                'status' => 'cancelled'
            ];
            $leaveRequestDetail = $this->leaveService->findLeaveRequestDetailByIdAndEmployeeId($leaveRequestId,getAuthUserCode());
            if($leaveRequestDetail->status != 'pending'){
                throw new \Exception('Leave request cannot be cancelled once it is updated from pending state.',403);
            }
            $this->leaveService->cancelLeaveRequest($validatedData, $leaveRequestDetail);
            return AppHelper::sendSuccessResponse('Leave request cancelled successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}

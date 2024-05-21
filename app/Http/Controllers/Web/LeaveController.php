<?php

namespace App\Http\Controllers\Web;

use App\Helpers\AppHelper;
use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Models\LeaveRequestMaster;
use App\Repositories\LeaveTypeRepository;
use App\Requests\Leave\LeaveRequestStoreFromWeb;
use App\Requests\Leave\LeaveRequestStoreRequest;
use App\Services\Leave\LeaveService;
use App\Services\Notification\NotificationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LeaveController extends Controller
{
    private $view = 'admin.leaveRequest.';

    private LeaveService $leaveService;
    private LeaveTypeRepository $leaveTypeRepo;
    private NotificationService $notificationService;

    public function __construct(LeaveService $leaveService, LeaveTypeRepository $leaveTypeRepo,NotificationService $notificationService)
    {
        $this->leaveService = $leaveService;
        $this->leaveTypeRepo = $leaveTypeRepo;
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $this->authorize('list_leave_request');
        try {
            $filterParameters = [
                'leave_type' => $request->leave_type ?? null,
                'requested_by' => $request->requested_by ?? null,
                'month' => $request->month ?? null,
                'year' => $request->year ?? Carbon::now()->format('Y'),
                'status' => $request->status ?? null
            ];
            if(AppHelper::ifDateInBsEnabled()){
                $nepaliDate = AppHelper::getCurrentNepaliYearMonth();
                $filterParameters['year'] = $request->year ?? $nepaliDate['year'];
            }
            $leaveTypes = $this->leaveTypeRepo->getAllLeaveTypes(['id','name']);
            $months = AppHelper::MONTHS;
            $with = ['leaveType:id,name', 'leaveRequestedBy:id,name'];
            $select = ['leave_requests_master.*'];
            $leaveDetails = $this->leaveService->getAllEmployeeLeaveRequests($filterParameters,$select, $with);
            return view($this->view . 'index',
                compact('leaveDetails', 'filterParameters',  'leaveTypes','months') );
         } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function show($leaveId)
    {
        try {
            $select = ['reasons', 'admin_remark'];
            $leaveRequest = $this->leaveService->findEmployeeLeaveRequestById($leaveId, $select);
            $leaveRequest->reasons = strip_tags($leaveRequest->reasons);
            return response()->json([
                'data' => $leaveRequest,
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function updateLeaveRequestStatus(Request $request, $leaveRequestId)
    {
        $this->authorize('update_leave_request');

        $validatedData = $request->validate([
            'status' => ['required', 'string', Rule::in(LeaveRequestMaster::STATUS)],
            'admin_remark' => ['nullable', 'required_if:status,rejected', 'string', 'min:10'],
        ]);

        try {
            DB::beginTransaction();

            $leaveRequestDetail = $this->leaveService->updateLeaveRequestStatus($validatedData, $leaveRequestId);

            if ($leaveRequestDetail) {
                $notificationData = [
                    'title' => 'Leave Status Update',
                    'type' => 'leave',
                    'user_id' => [$leaveRequestDetail->requested_by],
                    'description' => 'Your ' . $leaveRequestDetail->no_of_days . ' day leave request requested on ' . date('M d Y h:i A', strtotime($leaveRequestDetail->leave_requested_date)) . ' has been ' . ucfirst($validatedData['status']),
                    'notification_for_id' => $leaveRequestId,
                ];

                $notification = $this->notificationService->store($notificationData);

                if($notification){
                    $this->sendLeaveStatusNotification($notification,$leaveRequestDetail->requested_by);
                }
            }
            DB::commit();
            return redirect()
                ->route('admin.leave-request.index')
                ->with('success', 'Status Updated Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    private function sendLeaveStatusNotification($notificationData,$userId)
    {
        SMPushHelper::sendLeaveStatusNotification($notificationData->title, $notificationData->description,$userId);
    }

    public function createLeaveRequest()
    {
        $this->authorize('request_leave');
        try {
            return view($this->view . 'create');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function storeLeaveRequest(LeaveRequestStoreFromWeb $request)
    {
        $this->authorize('request_leave');
        try {
            $validatedData = $request->validated();
            $permissionKeyForNotification = 'employee_leave_request';

            DB::beginTransaction();
                $leaveRequest = $this->leaveService->storeLeaveRequest($validatedData);
            DB::commit();
            AppHelper::sendNotificationToAuthorizedUser(
                'Leave Request Notification',
                ucfirst(auth()->user()->name). ' has requested ' .$leaveRequest['no_of_days'] .
                ' day(s) leave from ' .AppHelper::formatDateForView($leaveRequest['leave_from']).
                ' on ' .AppHelper::convertLeaveDateFormat($leaveRequest['leave_requested_date']) . ' Reason: '.$validatedData['reasons'],
                $permissionKeyForNotification
            );
            return redirect()
                ->back()
                ->with('success', 'Leave request submitted Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()
                ->with('danger', $exception->getMessage())
                ->withInput();
        }
    }


}

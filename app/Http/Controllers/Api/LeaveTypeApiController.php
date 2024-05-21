<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Repositories\LeaveTypeRepository;
use App\Resources\Leave\LeaveTypeCollection;
use Exception;
use Illuminate\Http\JsonResponse;

class LeaveTypeApiController extends Controller
{
    public LeaveTypeRepository $leaveTypeRepo;

    public function __construct(LeaveTypeRepository $leaveTypeRepo)
    {
        $this->leaveTypeRepo = $leaveTypeRepo;
    }

    public function getAllLeaveTypeWithEmployeeLeaveRecord(): JsonResponse
    {
        try {
            $filterParameters = AppHelper::yearDetailToFilterData();
            $leaveType = $this->leaveTypeRepo->getAllLeaveTypesWithLeaveTakenbyEmployee($filterParameters);
            $getAllLeaveType = new LeaveTypeCollection($leaveType);
            return AppHelper::sendSuccessResponse('Data Found', $getAllLeaveType);
        } catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), 400);
        }
    }

}

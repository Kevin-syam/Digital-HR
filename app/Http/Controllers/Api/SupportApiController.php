<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Helpers\AttendanceHelper;
use App\Helpers\SMPush\SMPushHelper;
use App\Http\Controllers\Controller;
use App\Repositories\DepartmentRepository;
use App\Repositories\SupportRepository;
use App\Requests\Support\SupportStoreRequest;
use App\Resources\Support\SupportQueryListApiCollection;
use App\Resources\Support\SupportResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupportApiController extends Controller
{
    public SupportRepository $supportRepo;
    public DepartmentRepository $departmentRepo;

    public function __construct(SupportRepository $supportRepo,DepartmentRepository $departmentRepo)
    {
        $this->supportRepo = $supportRepo;
        $this->departmentRepo = $departmentRepo;
    }

    public function store(SupportStoreRequest $request): JsonResponse
    {
        try {
            $this->authorize('query_create');
            $validatedData = $request->validated();
            $permissionKeyForNotification  = 'employee_support';

            $detail = $this->supportRepo->store($validatedData);
            if($detail){
                AppHelper::sendNotificationToAuthorizedUser(
                'Support Notification',
                ucfirst($detail->createdBy->name) . 'has requested for support.',
                    $permissionKeyForNotification
                );
            }

            return AppHelper::sendSuccessResponse(
                'Query Submitted Successfully',
                new SupportResource($detail)
            );
        } catch (Exception $e) {
            return AppHelper::sendErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function getAuthUserBranchDepartmentLists()
    {
        try{
            $departmentLists = $this->departmentRepo->getDepartmentListUsingAuthUserBranchId();
            return AppHelper::sendSuccessResponse('Data Found',$departmentLists);
        }catch (Exception $exception){
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

    public function getAllAuthUserSupportQueryList(Request $request)
    {
        try{
            $filterParameters = [
                'user_id' => getAuthUserCode(),
                'per_page' => $request->per_page ?? 10
            ];
            $queryLists = $this->supportRepo->getAuthUserSupportQueryListPaginated($filterParameters);
            $data =  new SupportQueryListApiCollection($queryLists);
            return AppHelper::sendSuccessResponse('Data Found',$data);
        }catch(Exception $exception){
            return AppHelper::sendErrorResponse($exception->getMessage(), $exception->getCode());
        }
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Resources\Project\ProjectCollection;
use App\Resources\Project\ProjectResource;
use App\Services\Project\ProjectService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class ProjectApiController extends Controller
{
    private ProjectService $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function getUserAssignedAllProjects(Request $request)
    {
        try {
            $perPage = $request->per_page ?? 20;
            $select = ['*'];
            $with = [
                'assignedMembers.user:id,name,avatar',
                'client:id,name'
            ];
            $projectLists = $this->projectService->getAllActiveProjectOfEmployeePaginated(getAuthUserCode(),$select,$with,$perPage);
            return new ProjectCollection($projectLists);
        }catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), 400);
        }
    }

    public function getProjectDetailById($projectId): JsonResponse
    {
        try {
            $select = ['*'];
            $with = [
                'getOnlyEmployeeAssignedTask:project_id,name,id,status,priority,start_date,end_date,is_active',
                'assignedMembers.user:id,name,avatar,post_id',
                'assignedMembers.user.post:id,post_name',
                'client:id,name',
                'projectAttachments',
                'projectLeaders.user:id,post_id,name,avatar',
                'projectLeaders.user.post:id,post_name'
            ];
            $detail = $this->projectService->findAssignedMemberProjectDetailById($projectId,$with,$select);
            $projectDetail = new ProjectResource($detail);
            return AppHelper::sendSuccessResponse('Data Found',$projectDetail);
        } catch (Exception $exception) {
            return AppHelper::sendErrorResponse($exception->getMessage(), 400);
        }
    }

}

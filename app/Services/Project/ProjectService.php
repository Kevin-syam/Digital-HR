<?php

namespace App\Services\Project;

use App\Helpers\AppHelper;
use App\Models\Project;
use App\Repositories\AttachmentRepository;
use App\Repositories\ProjectRepository;
use App\Traits\ImageService;
use Exception;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    use ImageService;

    private ProjectRepository $projectRepo;
    private AttachmentRepository $attachmentRepo;

    public function __construct(ProjectRepository $projectRepo,AttachmentRepository $attachmentRepo)
    {
        $this->projectRepo = $projectRepo;
        $this->attachmentRepo = $attachmentRepo;
    }

    public function getAllFilteredProjectsPaginated($filterParameter,$select = ['*'], $with = [])
    {
        return $this->projectRepo->getAllFilteredProjects($filterParameter, $select, $with);
    }

    public function getAllActiveProjects($select=['*'],$with=[])
    {
        return $this->projectRepo->getAllActiveProject($select, $with);
    }

    public function findProjectDetailById($id,$with=[],$select = ['*'])
    {
        return $this->projectRepo->findProjectDetailById($id,$with,$select);
    }

    public function getAllProjectLists($select=['*'])
    {
        return $this->projectRepo->getAllProjectLists($select);
    }

    public function getProjectCardData()
    {
        $projectDetail =  $this->projectRepo->getAllProjectDetailForDashboardCard();
        $data = [
            'not_started' => $projectDetail['not_started'] ?? 0,
            'on_hold' => $projectDetail['on_hold'] ?? 0,
            'in_progress' => $projectDetail['in_progress'] ?? 0,
            'completed' => $projectDetail['completed'] ?? 0,
            'cancelled' => $projectDetail['cancelled'] ?? 0
        ];
        $data['total_projects'] = array_sum($data);

        return $data;
    }

    public function getRecentProjectListsForDashboard($select=['*'],$with=[])
    {
        return $this->projectRepo->getRecentProjectListsForDashboard($select,$with);
    }

    public function findAssignedMemberProjectDetailById($projectId,$with=[],$select = ['*'])
    {
        try{
            $detail = $this->projectRepo->findAssignedMemberProjectDetailById($projectId,$with,$select);
            if(!$detail){
                throw new Exception('Project Detail Not Found',404);
            }
            return $detail;
        }catch(Exception $exception){
            throw $exception;
        }

    }

    public function getAllActiveProjectsOfEmployee($employeeId,$select=['*'],$with=[])
    {
        return $this->projectRepo->getAllActiveProjectsOfEmployee($employeeId,$with,$select);
    }

    public function getAllActiveProjectOfEmployeePaginated($employeeId,$select=['*'],$with=[],$perPage)
    {
        return $this->projectRepo->getAllActiveProjectsOfEmployeePaginated($employeeId,$with,$select,$perPage);
    }
    /**
     * @throws \Exception
     */
    public function saveProjectDetail($validatedData)
    {
        try{
            $leaderMemberData = $this->getLeaderAndMemberData($validatedData);
            DB::beginTransaction();
            $validatedData['cover_pic'] = $this->storeImage($validatedData['cover_pic'],Project::UPLOAD_PATH);
            $projectDetail = $this->projectRepo->store($validatedData);
            $this->projectRepo->saveProjectTeamLeader($projectDetail,$leaderMemberData['leader']);
            $this->projectRepo->assignMemberToProject($projectDetail,$leaderMemberData['member']);
            if(isset($validatedData['attachments'])){
                $projectFiles = $this->attachmentRepo->prepareAttachmentData($validatedData['attachments']);
                $this->attachmentRepo->saveProjectAttachment($projectDetail,$projectFiles);
            }
            DB::commit();
            return $projectDetail;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProjectDetail($validatedData, $projectId): bool
    {
        try {
            $with = ['projectAttachments'];
            $projectDetail = $this->findProjectDetailById($projectId,$with);
            if (!$projectDetail) {
                throw new \Exception('Project Detail Not Found', 404);
            }
//            if(isset($validatedData['attachments']) && count($projectDetail->projectAttachments) > 0){
//                $this->attachmentRepo->removeOldAttachments($projectDetail->projectAttachments);
//            }
            DB::beginTransaction();
            if(isset($validatedData['cover_pic'])){
                $this->removeImage(Project::UPLOAD_PATH, $projectDetail['cover_pic']);
                $validatedData['cover_pic'] = $this->storeImage($validatedData['cover_pic'],Project::UPLOAD_PATH);
            }
            $updateStatus = $this->projectRepo->update($projectDetail, $validatedData);
            if(!$updateStatus){
                throw new \Exception('Something went wrong !',400);
            }

            if(isset($validatedData['attachments'])){
                $projectFiles = $this->attachmentRepo->prepareAttachmentData($validatedData['attachments']);
                $this->attachmentRepo->saveProjectAttachment($projectDetail,$projectFiles);
            }
            $leaderMemberData = $this->getLeaderAndMemberData($validatedData);
            $this->projectRepo->saveProjectTeamLeader($projectDetail,$leaderMemberData['leader']);
            $this->projectRepo->assignMemberToProject($projectDetail,$leaderMemberData['member']);

            DB::commit();
            return $updateStatus;
        }catch (\Exception $e){
            DB::rollBack();
            throw $e;
        }
    }

    private function getLeaderAndMemberData($validatedData): array
    {
        try{
            $teamLeaderArray = [];
            $assignedMemberArray = [];
            foreach ($validatedData['project_leader'] as $key => $leader){
                $teamLeaderArray[$key]['leader_id'] = $leader;
//                if(!in_array($value,$validatedData['assigned_member'])){
//                    $validatedData['assigned_member'][] = $value;
//                }
            }
            foreach ($validatedData['assigned_member'] as $key => $value){
                $assignedMemberArray[$key]['member_id'] = $value;
            }
            $data['leader'] = $teamLeaderArray;
            $data['member'] = $assignedMemberArray;
            return $data;
        }catch(\Exception $exception){
            throw $exception;
        }
    }

    public function toggleStatus($id): bool
    {
        try{
            DB::beginTransaction();
                $projectDetail = $this->findProjectDetailById($id);
                $this->projectRepo->toggleStatus($projectDetail);
            DB::commit();
            return true;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteProjectDetail($id): bool
    {
        try{
            $projectDetail = $this->findProjectDetailById($id);
            if(!$projectDetail){
                throw new \Exception('Project Detail Not Found',404);
            }
            DB::beginTransaction();
            if(count($projectDetail->projectAttachments) > 0){
                $this->attachmentRepo->removeOldAttachments($projectDetail->projectAttachments);
            }
            $status  = $this->projectRepo->delete($projectDetail);
            DB::commit();
            return $status;
        }catch(\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
    }

    public function updateProjectProgressStatus($projectDetail)
    {
        try{
            $completedStatus = 'completed';
            $notCompletedStatus = 'in_progress';
            $projectProgress = $projectDetail->getProjectProgressInPercentage();
            $projectStatus = (intval($projectProgress) == 100) ? $completedStatus : $notCompletedStatus;
            return $this->projectRepo->changeProjectProgressStatus($projectDetail,$projectStatus);
        }catch(Exception $e){
            throw $e;
        }

    }

    public function getAllLeaderDetailAssignedInProject($projectId)
    {
        try{
            return $this->projectRepo->getAllLeaderDetailAssignedInProject($projectId);
        }catch(Exception $e){
            throw $e;
        }
    }

    public function getAllMemberDetailAssignedInProject($projectId)
    {
        try{
            return $this->projectRepo->getAllMemberDetailAssignedInProject($projectId);
        }catch(Exception $e){
            throw $e;
        }
    }

    public function updateLeadersOfProject($projectDetail,$validatedData)
    {
        try{
            $assignedMemberArray = [];
            foreach ($validatedData['employee'] as $value) {
                $assignedMemberArray[] = ['leader_id' => $value];
            }
            return $this->projectRepo->updateProjectLeader($projectDetail,$assignedMemberArray);
        }catch(Exception $e){
            throw $e;
        }
    }

    public function updateMemberOfProject($projectDetail,$validatedData)
    {
        try{
            $assignedMemberArray = [];
            foreach ($validatedData['employee'] as $value) {
                $assignedMemberArray[] = ['member_id' => $value];
            }
            return $this->projectRepo->updateProjectMember($projectDetail,$assignedMemberArray);
        }catch(Exception $e){
            throw $e;
        }
    }
}

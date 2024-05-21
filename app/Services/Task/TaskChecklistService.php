<?php

namespace App\Services\Task;

use App\Helpers\AppHelper;
use App\Repositories\TaskChecklistRepository;
use App\Repositories\TaskRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class TaskChecklistService
{
    private TaskChecklistRepository $taskChecklistRepo;
    private TaskRepository $taskRepo;

    public function __construct(TaskChecklistRepository $taskChecklistRepo,TaskRepository $taskRepo)
    {
        $this->taskChecklistRepo = $taskChecklistRepo;
        $this->taskRepo = $taskRepo;
    }

    public function saveTaskCheckLists($validatedData)
    {
        try {
            $notCompletedStatus = 'in_progress';
            $checklists = [];
            DB::beginTransaction();
            foreach ($validatedData['name'] as $key => $value) {
                $checklists[$key]['task_id'] = $validatedData['task_id'];
                $checklists[$key]['name'] = $value;
                $checklists[$key]['assigned_to'] = $validatedData['assigned_to'][$key];
            }
            $checkListsInsertStatus = $this->taskChecklistRepo->createManyChecklist($checklists);
            if($checkListsInsertStatus){
                $taskDetail = $this->taskRepo->findTaskDetailById($validatedData['task_id']);
                $this->taskRepo->changeTaskStatus($taskDetail,$notCompletedStatus);
            }
            DB::commit();
            return $checkListsInsertStatus;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function findTaskChecklistDetail($checklistId, $select = ['*'], $with = [])
    {
        try {
            $checklistDetail = $this->taskChecklistRepo->findTaskChecklistDetailById($checklistId, $select, $with);
            if (!$checklistDetail) {
                throw new Exception('Task Checklist Detail Not Found', 400);
            }
            return $checklistDetail;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function findTaskChecklistOfAssignedUserById($userId,$checklistId,$select=['*'],$with=[])
    {
        try {
            $checklistDetail = $this->taskChecklistRepo->findTaskChecklistOfAssignedUserById($userId,$checklistId,$select,$with);
            if (!$checklistDetail) {
                throw new Exception('Task Checklist Detail Not Found', 400);
            }
            return $checklistDetail;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateTaskChecklistDetail($validatedData, $taskChecklistId)
    {
        try {
            $checklistDetail = $this->findTaskChecklistDetail($taskChecklistId);
            DB::beginTransaction();
            $update = $this->taskChecklistRepo->update($checklistDetail, $validatedData);
            DB::commit();
            return $update;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function deleteTaskChecklistDetail($id)
    {
        try {
            $completedStatus = 'completed';
            $taskChecklistDetail = $this->findTaskChecklistDetail($id);
            if (!$taskChecklistDetail) {
                throw new Exception('Task Checklist Detail Not Found', 404);
            }
            DB::beginTransaction();
            $deleteStatus = $this->taskChecklistRepo->delete($taskChecklistDetail);
            if($deleteStatus){
                if(intval($taskChecklistDetail->task->getTaskProgressInPercentage()) == 100){
                    $this->taskRepo->changeTaskStatus($taskChecklistDetail->task,$completedStatus);
                }
            }
            DB::commit();
            return $deleteStatus;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function toggleIsCompletedChecklistStatus($id)
    {
        try {
            $taskChecklistDetail = $this->findTaskChecklistDetail($id);
            DB::beginTransaction();
                $status = $this->taskChecklistRepo->toggleIsCompletedStatus($taskChecklistDetail);
                if($status){
                    $this->changeTaskStatus($taskChecklistDetail);
                }
            DB::commit();
            return $status;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function toggleIsCompletedStatusByAssignedUserOnly($id)
    {
        try {
            $select = ['id','is_completed','name','task_id'];
            $with = ['task:id,name,status,project_id'];
            $userAssignedChecklist = $this->findTaskChecklistOfAssignedUserById(getAuthUserCode(),$id,$select,$with);
            DB::beginTransaction();
            $status = $this->taskChecklistRepo->toggleIsCompletedStatus($userAssignedChecklist);
            if($status){
                $this->changeTaskStatus($userAssignedChecklist);
            }
            DB::commit();
            return [
                    'checklist_id' => $userAssignedChecklist->id,
                    'is_completed' => $userAssignedChecklist->is_completed,
                    'name' => $userAssignedChecklist->name,
                ];
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function changeTaskStatus($userAssignedChecklist)
    {
        try{
            $completedStatus = 'completed';
            $notCompletedStatus = 'in_progress';
            $taskProgress = $userAssignedChecklist->task?->getTaskProgressInPercentage();
            $taskStatus = (intval($taskProgress) == 100) ? $completedStatus : $notCompletedStatus;
            return $this->taskRepo->changeTaskStatus($userAssignedChecklist->task,$taskStatus);
        }catch(Exception $e){
            throw $e;
        }

    }
}

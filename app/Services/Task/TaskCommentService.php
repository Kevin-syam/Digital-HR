<?php

namespace App\Services\Task;

use App\Helpers\AppHelper;
use App\Repositories\TaskCommentRepository;
use App\Repositories\TaskRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class TaskCommentService
{
    public TaskCommentRepository $commentRepo;
    public TaskRepository $taskRepo;

    public function __construct(TaskCommentRepository $commentRepo,
                                TaskRepository        $taskRepo)
    {
        $this->commentRepo = $commentRepo;
        $this->taskRepo = $taskRepo;
    }

    public function getAllTaskCommentsByTaskId($filterParameters,$select=['*'],$with=[])
    {
        try{
            return $this->commentRepo->getCommentsPaginatedByTaskId($filterParameters,$select,$with);
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function storeTaskCommentDetail($validatedData)
    {
        try {
            $this->canCommentOnTask($validatedData['task_id']);
            DB::beginTransaction();
            $comment = $this->commentRepo->storeComment($validatedData);
            if ($comment) {
                if (isset($validatedData['mentioned'])) {
                    $mentionedData = $this->prepareDataForMentionMember($validatedData['mentioned']);
                    $this->commentRepo->createMentionedMemberInComment($comment, $mentionedData);
                }
            }
            DB::commit();
            return $comment;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function canCommentOnTask($taskId): void
    {
        try {
            if (AppHelper::getAuthUserRole() == 'admin') {
                return;
            }
            $taskDetail = $this->taskRepo->findAssignedMemberTaskDetailById($taskId, $with = [], ['*']);
            if (!$taskDetail) {
                throw new Exception('sorry you cannot comment on this task', 403);
            }
            return;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function prepareDataForMentionMember($mentionedData): array
    {
        try {
            $mentionedArray = [];
            foreach ($mentionedData as $key => $value) {
                $mentionedArray[$key]['member_id'] = $value;
            }
            return $mentionedArray;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function storeCommentReply($validatedData)
    {
        try {

            $this->canCommentOnTask($validatedData['task_id']);
            DB::beginTransaction();
            $reply = $this->commentRepo->storeCommentReply($validatedData);
            if (isset($validatedData['mentioned'])) {
                $mentionedData = $this->prepareDataForMentionMember($validatedData['mentioned']);
                $this->commentRepo->createMentionedMemberInReply($reply, $mentionedData);
            }
            DB::commit();
            return $reply;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteTaskComment($commentId)
    {
        try {
            $commentDetail = $this->findCommentDetailById($commentId);
            DB::beginTransaction();
            if ((AppHelper::getAuthUserRole() != 'admin') &&
                (getAuthUserCode() != $commentDetail->created_by)
            ) {
                throw new Exception('Sorry, You cannot delete this comment', 403);
            }
            $status = $this->commentRepo->deleteComment($commentDetail);
            DB::commit();
            return $status;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function findCommentDetailById($id, $with = [], $Select = ['*'])
    {
        try {
            $commentDetail = $this->commentRepo->findCommentById($id, $with, $Select);
            if (!$commentDetail) {
                throw new Exception('Comment Detail Not Found', 404);
            }
            return $commentDetail;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function deleteReply($replyId)
    {
        try {
            $replyDetail = $this->findReplyDetailById($replyId);
            DB::beginTransaction();
            if ((AppHelper::getAuthUserRole() != 'admin') &&
                (getAuthUserCode() != $replyDetail->created_by)
            ) {
                throw new Exception('Sorry, You cannot delete this comment reply', 403);
            }
            $status = $this->commentRepo->deleteReply($replyDetail);
            DB::commit();
            return $status;
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function findReplyDetailById($id, $with = [], $Select = ['*'])
    {
        try {
            $replyDetail = $this->commentRepo->findCommentReplyById($id, $with, $Select);
            if (!$replyDetail) {
                throw new Exception('Reply Detail Not Found', 404);
            }
            return $replyDetail;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

}

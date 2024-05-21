<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Requests\Task\TaskCheckListUpdateRequest;
use App\Services\Task\TaskChecklistService;
use App\Services\Task\TaskService;
use App\Requests\Task\TaskChecklistStoreRequest;
use Illuminate\Support\Facades\DB;
use Exception;

class TaskChecklistController extends Controller
{
    private $view = 'admin.task.';

    public TaskService $taskService;
    public TaskChecklistService $taskChecklistService;

    public function __construct(TaskService $taskService,TaskChecklistService $taskChecklistService )
    {
        $this->taskService = $taskService;
        $this->taskChecklistService = $taskChecklistService;
    }

    public function store(TaskChecklistStoreRequest $request)
    {
        $this->authorize('create_checklist');
        try {
            $validatedData = $request->validated();
            DB::beginTransaction();
                $this->taskChecklistService->saveTaskCheckLists($validatedData);
            DB::commit();
            return redirect()
                ->route('admin.tasks.show',$validatedData['task_id'])
                ->with('success', 'Task Checklists Added Successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('danger', $e->getMessage());
        }
    }

    public function edit($checkListId)
    {
        $this->authorize('edit_checklist');
        try{
            $select = ['*'];
            $with = ['task.assignedMembers.user:id,name'];
            $checklistDetail = $this->taskChecklistService->findTaskChecklistDetail($checkListId,$select,$with);
            return view($this->view.'task_checklist_edit', compact('checklistDetail'));
        }catch(\Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function update(TaskCheckListUpdateRequest $request, $taskChecklistId)
    {
        $this->authorize('edit_checklist');
        try {
            $validatedData = $request->validated();
            $this->taskChecklistService->updateTaskChecklistDetail($validatedData, $taskChecklistId);
            return redirect()
                ->back()
                ->with('success', 'Task Checklist Updated Successfully');
        } catch (Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        $this->authorize('delete_checklist');
        try {
            DB::beginTransaction();
                $this->taskChecklistService->deleteTaskChecklistDetail($id);
            DB::commit();
            return redirect()->back()->with('success', 'Task Checklist Deleted  successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function toggleIsCompletedStatus($checklistId)
    {
        $this->authorize('edit_checklist');
        try {
            DB::beginTransaction();
                $this->taskChecklistService->toggleIsCompletedChecklistStatus($checklistId);
            DB::commit();
            return redirect()->back()->with('success', 'Task Checklist status changed successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

}

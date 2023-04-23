<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\UserTask;
use Exception;

class TaskController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try
        {
            $tasks = Task::all();
            return $this->successResponse(TaskResource::collection($tasks), 'Tasks Found Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in TaskController.index', $e->getMessage(), 500);
        }
    }

    /**
     * Display a listing of all recent tasks.
     */
    public function recentTasks()
    {
        try
        {
            $tasks = Task::latest()->take(10)->get();
            return $this->successResponse(TaskResource::collection($tasks), 'Recent Tasks Found Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in TaskController.index', $e->getMessage(), 500);
        }
    }

    /**
     * Get All Tasks Count
     */
    public function allTasksCount()
    {
        try
        {
            $tasks = Task::all();
            return $this->successResponse(count($tasks), 'Tasks Count Found Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in TaskController.allTasksCount', $e->getMessage(), 500);
        }
    }

    /**
     * Get All Assigned Tasks Count
     */
    public function allAssignedTasksCount()
    {
        try
        {
            $user_tasks = UserTask::all();
            return $this->successResponse(count($user_tasks), 'Assigned Tasks Count Found Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in TaskController.allAssignedTasksCount', $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        try
        {
            $task = new Task();
            $task->name = $request->name;
            $task->description = $request->description;
            $task->due_date = $request->due_date;
            $task->status_id = $request->status_id;
            
            $task->save();
            $task->users()->attach($request->selected_users, [
                'due_date' => $request->due_date,
                'start_time' => $request->start_date,
                'end_time' => $request->end_date,
                'remarks' => $request->remarks,
                'status_id' => $request->status_id,
            ]);

            return $this->successResponse(new TaskResource($task), 'Task Saved Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in TaskController.store', $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        try
        {
            return $this->successResponse(new TaskResource($task), 'Task Found Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in TaskController.show', $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try
        {
            $response = $task->update($request->all());
            $task->users()->syncWithPivotValues($request->selected_users, [
                'due_date' => $request->due_date,
                'start_time' => $request->start_date,
                'end_time' => $request->end_date,
                'remarks' => $request->remarks,
                'status_id' => $request->status_id,
            ]);

            return $this->successResponse(new TaskResource($task), 'Task Updated Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in TaskController.update', $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try
        {
            $response = $task->delete();
            return $this->successResponse(new TaskResource($task), 'Task Deleted Successfully');
        }
        catch (Exception $e)
        {
            return $this->errorResponse('Something went wrong in TaskController.destroy', $e->getMessage(), 500);
        }
    }
}

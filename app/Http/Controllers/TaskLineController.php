<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskLine;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class TaskLineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        //
        $task = Task::findOrFail($id);
        $taskId = $task->id;
        $users = User::with('profile')->whereDoesntHave('taskLines', function ($q) use ($taskId) {
            $q->where('task_id', $taskId);
        })->get();

        return view('tasks.task-lines.create', compact('task', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        //
        $request->validate([
            'user_id' => 'required|numeric',
        ]);
        $task = Task::findOrFail($id);
        try {
            $task->taskLines()->create([
                'user_id' => $request->user_id,
                'status' => 0,
            ]); // user IDs

            return redirect()->route('tasks.show', $task)->with('success', "Successfully updated");
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskLine $taskLine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskLine $taskLine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task, TaskLine $taskLine)
    {
        //
        try {
            $taskLine->update([
                'status' => $taskLine->status ? 0 : 1,
            ]);
            return redirect()->back()->with('success', "Successfully updated");
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, TaskLine $taskLine)
    {
        //
        // $model = Assignment::findOrFail($id);
        try {
            $taskLine->delete();
            return redirect()->back()->with('success', 'Successfully deleted');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }
}

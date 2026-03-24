<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $users = User::all();
        return view('tasks.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'description' => 'required',
            'due_date' => 'required|date',
            'grouped' => 'nullable',
            'user_ids_array' => 'required_if:grouped,on',
        ]);

        $userIdsArray = array();
        $userIdsArray = $request->user_ids_array;

        // $grades = Grade::whereIn('id', $gradeIdsArray)->get();

        DB::beginTransaction();
        try {
            $task = Task::create([
                'description' => $request->description,
                'due_date' => $request->due_date,
            ]);

            if ($request->grouped) {

                $userIdsArray = array();
                $userIdsArray = $request->user_ids_array;
                // Assign to multiple users
                $task->users()->attach($userIdsArray); // user IDs
            } else {

                $task->taskLines()->create([
                    'user_id' => Auth::user()->user?->id,
                ]);
            }
            DB::commit();
            return redirect()->route('tasks.index')->with('success', 'Successfully created');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $task = Task::findOrFail($id);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'description' => 'required',
            'due_date' => 'required|date',
        ]);

        $model = Task::findOrFail($id);
        try {
            $model->update($request->all());
            return redirect()->route('tasks.index')->with('success', 'Successfully updated');
        } catch (Exception $ex) {
            return redirect()->back()->withErrors($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
        try {
            $task->delete();
            return redirect()->route('tasks.index')->with('success', 'Successfully deleted');
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
            // something went wrong
        }
    }
}

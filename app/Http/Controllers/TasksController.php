<?php

namespace App\Http\Controllers;

use App\Task;
use App\Http\Requests\CreateTaskRequest;

class TasksController extends Controller
{
    public function index() {
        $tasks = Task::orderByDesc('id')->get();

        return response()->json([
            'tasks' => $tasks
        ], 200);
    }

    public function store(CreateTaskRequest $request) {
        $task = Task::create($request->all());

        return response()->json([
            'task' => $task
        ], 200);
    }

    public function destroy(Task $task) {
        $task->delete();

        return response()->json([
            'message' => 'Task is deleted.'
        ], 200);
    }

    public function show(Task $task) {
        return response()->json([
            'task' => $task
        ], 200);
    }

    public function update(Task $task) {
        return response()->json([
            'task' => tap($task)->update(['finished' => 1])
        ], 200);
    }

    public function getUnfinished() {
        $tasks = Task::where('finished', 0)->orderByDesc('id')->get();

        return response()->json([
            'tasks' => $tasks
        ], 200);
    }
}

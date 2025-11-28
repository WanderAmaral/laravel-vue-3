<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('started_at', 'ASC')->paginate(10);

        return Inertia::render('tasks/Index', [
            'tasks' => $tasks,
        ]);
    }

    public function show(Task $task)
    {  
        return Inertia::render('tasks/Show', [
            'task' => $task,
        ]);
        // dd($task);
    }
}

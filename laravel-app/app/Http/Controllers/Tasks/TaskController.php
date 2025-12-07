<?php

namespace App\Http\Controllers\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

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
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'started_at'  => ['required', 'date'],
            'finished_at' => ['nullable', 'date', 'after_or_equal:started_at'],
        ]);


        if (now()->gt($data['started_at'])) {
            $data['started_at'] = now();
        }

        $data['user_id'] = \Illuminate\Support\Facades\Auth::id();

        Task::create($data);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task criada com sucesso!');
    }


    public function edit(Task $task)
    {

        $task->update();
    }
}

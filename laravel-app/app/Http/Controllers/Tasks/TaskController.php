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
    public function store(Request $request)
    {
        // 1) Validar o que veio do Vue
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'started_at'  => ['nullable', 'date'],
            'finished_at' => ['nullable', 'date', 'after_or_equal:started_at'],
        ]);

        Task::create($data);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task criada com sucesso!');
    }
}

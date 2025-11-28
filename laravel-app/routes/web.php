<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Tasks\TaskController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');


Route::group(['middleware' => 'auth', 'verified'], function () {

    //Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    //Tarefas
    Route::prefix('tasks')->group(function () {

        //Listar Tarefas
        Route::get('/', [TaskController::class, 'index'])
            ->name('tasks.index');

        //Criar Tarefa
        Route::get('/create', [TaskController::class, 'create'])
            ->name('tasks.create');

        //Visualizar Tarefa
        Route::get('/{task}', [TaskController::class, 'show'])
            ->name('tasks.show');

        //Editar Tarefa
        Route::get('/{task}/edit', [TaskController::class, 'edit'])
            ->name('tasks.edit');
        Route::put('/{task}', [TaskController::class, 'update'])
            ->name('tasks.update');

        //Apagar Tarefa
        Route::delete('/{task}', [TaskController::class, 'destroy'])
            ->name('tasks.destroy');
    });
});

require __DIR__ . '/settings.php';

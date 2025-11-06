<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TarefaController;
use Illuminate\Support\Facades\Route;

//rotas de autenticação publicas
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.store');

//rotas protegidas
Route::middleware('auth')->group(function () {

    Route::get('/home', [TarefaController::class, 'index'])->name('restrict.home');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //rotas para soft delete. requisito 5
    Route::get('tarefas/lixeira', [TarefaController::class, 'lixeira'])->name('tarefas.lixeira');
    Route::post('tarefas/{id}/restore', [TarefaController::class, 'restore'])->name('tarefas.restore');

    //rota resource. requisito 7
    Route::resource('tarefas', TarefaController::class);    
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TodoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// api/auth/...
Route::prefix('auth')->group(function() {
    Route::post('/register', [UserController::class ,'register']);
    Route::post('/login', [UserController::class ,'login']);
});


// Todo
Route::prefix('todo')->middleware('auth:api')->group(function() { //middleware ekledk cunk , Token ile kontrol yapılıp erişim sağlanmalı
    Route::get('/list', [TodoController::class ,'list']);
    Route::get('/relation', [TodoController::class, 'relation']);
    Route::post('/store', [TodoController::class, 'store']);

    Route::get('/first_data/{id}',[TodoController::class , 'first_data']);

    Route::get('/users', [UserController::class, 'index']); //!
    Route::post('/update/{id}', [TodoController::class, 'update']);

    Route::delete('/delete/{id}', [TodoController::class, 'destroy']);

    // Scope
    Route::get('/scope/index', [TodoController::class, 'index']);
    Route::get('/scope/completedTodo', [TodoController::class, 'completedTodo']);

    Route::put('/update/{id}', [TodoController::class, 'update']); //!
});



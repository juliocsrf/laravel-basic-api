<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;

Route::get('/ping', function (){
    return ['pong' => true];
});

Route::get('/invalid-token', function(){
    return ['error' => 'Invalid token'];
})->name('login');

Route::post('/user', [AuthController::class, 'create']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post('/auth/logout', [AuthController::class, 'logout']);
Route::middleware('auth:api')->get('auth/me', [AuthController::class, 'me']);

Route::post('/todo', [ApiController::class, 'createTodo']);
Route::get('/todos', [ApiController::class, 'readAllTodos']);
Route::get('todo/{id}', [ApiController::class, 'readTodo']);
Route::put('/todo/{id}', [ApiController::class, 'updateTodo']);
Route::delete('/todo/{id}', [ApiController::class, 'deleteTodo']);
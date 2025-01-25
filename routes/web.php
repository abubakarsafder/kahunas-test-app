<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatGPTController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/create-role', [AuthController::class, 'createRole']);
Route::get('/', [ChatGPTController::class, 'index']);

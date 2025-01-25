<?php

use App\Http\Controllers\ChatGPTController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ChatGPTController::class, 'index']);

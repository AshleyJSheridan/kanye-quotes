<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuotesController;

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

Route::post('/user/register', [AuthController::class, 'register'])->name('user.register');
Route::post('/user/login', [AuthController::class, 'login'])->name('user.login');
Route::post('/user/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('user.logout');

Route::get('/quotes', [QuotesController::class, 'getQuotes'])->middleware('auth:sanctum')->name('');
Route::post('/quotes/refresh', [QuotesController::class, 'refreshQuotes'])->middleware('auth:sanctum');

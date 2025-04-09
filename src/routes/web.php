<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BreakingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TimeSearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::get('/',[AttendanceController::class,'index'])->middleware('verified');
Route::get('/work-start',[AttendanceController::class, 'startTimeAdd']);
Route::get('/work-end',[AttendanceController::class,'endTimeAdd']);
Route::get('/break-start',[BreakingController::class,'startTimeAdd']);
Route::get('/break-end',[BreakingController::class,'endTimeAdd']);
Route::get('/attendance',[TimeSearchController::class,'index']);
Route::post('/attendance', [TimeSearchController::class, 'index']);
Route::post('/user-work-list', [TimeSearchController::class, 'userListIndex']);
Route::get('/user-work-list', [TimeSearchController::class, 'userWorkListIndex']);
Route::get('/user-list',[UserController::class,'index']);

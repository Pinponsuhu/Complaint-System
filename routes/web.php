<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\HODController;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/login',[AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login',[AuthController::class, 'logining'])->middleware('guest');
Route::get('/register',[AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/register',[MailController::class, 'registerVerify'])->middleware('guest');
Route::get('/email/verify',[AuthController::class, 'verification_view'])->middleware('guest');
Route::post('/email/verify',[MailController::class, 'registering'])->name('verifying');
Route::get('/', [ComplaintController::class, 'dashboard'])->name('dashboard')->middleware(['auth','is_banned']);
Route::get('/lecturer/dashboard', [ComplaintController::class, 'lecturer_dashboard'])->name('lec_dashboard')->middleware(['auth','is_banned']);
Route::get('/complaint/{status}', [ComplaintController::class, 'index'])->name('complaint')->middleware(['auth','is_banned']);
Route::post('/complaint', [ComplaintController::class, 'add_new'])->name('add_complaint')->middleware(['auth','is_banned']);
Route::get('/complaint-trail/{complaint_id}', [ComplaintController::class, 'all_message'])->middleware(['auth','is_banned']);
Route::post('/send-message/{complaint_id}',[ComplaintController::class, 'send_message'])->name('send-message')->middleware(['auth','is_banned']);
Route::post('/status/{complaint_id}',[ComplaintController::class, 'change_status'])->middleware(['auth','is_banned']);
Route::get('/admin/dashboard',[AdminController::class, 'dashboard'])->name('admin_dashboard');
Route::post('/toggle/{user_id}',[AdminController::class, 'toggle_ban']);
Route::get('/all/students',[AdminController::class, 'students'])->name('students');
Route::get('/all/lecturers',[AdminController::class, 'lecturers'])->name('lecturers');
Route::get('/lecturers',[HODController::class, 'lecturers'])->middleware(['auth','is_head']);
Route::post('/lecturers',[HODController::class, 'new_lecturer'])->middleware(['auth','is_head']);
Route::get('/departments/{status}',[AdminController::class, 'department']);
Route::post('/departments',[AdminController::class, 'add_department'])->name('department');
Route::post('/department/{deprtment_id}',[AdminController::class, 'delete']);
Route::get('/levels',[AdminController::class, 'level'])->name('level');
Route::post('/levels',[AdminController::class, 'add_level']);
Route::post('/levels/{level_id}',[AdminController::class, 'update_level']);


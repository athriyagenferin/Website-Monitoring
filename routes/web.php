<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

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

// Route::get('/', function () {
//     return view('welcome');
// });


//real time
// Route::get('/', [AuthController::class, 'index'])->name('login');
// Route::post('login', [AuthController::class, 'login'])->name('login.post');

// // Auth Login & Logout
// Route::get('login', [AuthController::class, 'index'])->name('auth.index');
// Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
// Route::get('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

// // Dashboard
// Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')

// // Dashboard Real Time
// Route::get('/fetch-data', [DashboardController::class, 'fetchDataAPI']);



Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/fetch-data', [DashboardController::class, 'fetchDataAPI']);



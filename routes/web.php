<?php

use App\Http\Controllers\Superadmin\AkunController;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\DosenController;
use App\Http\Controllers\Superadmin\MatakuliahController;
use App\Http\Controllers\Superadmin\ProdiController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Superadmin
Route::group(['prefix' => 'superadmin', 'as' => 'superadmin.', 'middleware' => ['auth']], function () {
  
	// Dashboard
	Route::get('', [DashboardController::class, 'index'])->name('index');

  // Dosen
  Route::group(['prefix' => 'dosen', 'as' => 'dosen.'], function () {
		Route::get('', [DosenController::class, 'index'])->name('index');
		Route::post('', [DosenController::class, 'store'])->name('store');
		Route::put('', [DosenController::class, 'update'])->name('update');
		Route::put('/aktif', [DosenController::class, 'aktif'])->name('aktif');
	});

  // Matakuliah
  Route::group(['prefix' => 'matakuliah', 'as' => 'matakuliah.'], function () {
		Route::get('', [MatakuliahController::class, 'index'])->name('index');
	});

	// Prodi
  Route::group(['prefix' => 'prodi', 'as' => 'prodi.'], function () {
		Route::get('', [ProdiController::class, 'index'])->name('index');
	});

  // Akun
  Route::group(['prefix' => 'akun', 'as' => 'akun.'], function () {
		Route::get('', [AkunController::class, 'index'])->name('index');
		Route::post('', [AkunController::class, 'store'])->name('store');
		Route::put('', [AkunController::class, 'update'])->name('update');
		Route::put('/reset', [AkunController::class, 'reset'])->name('reset');
		Route::put('/aktif', [AkunController::class, 'aktif'])->name('aktif');
	});
		
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

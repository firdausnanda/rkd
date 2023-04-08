<?php

use App\Http\Controllers\Superadmin\AkunController;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\DosenController;
use App\Http\Controllers\Superadmin\MatakuliahController;
use App\Http\Controllers\Superadmin\PengajaranController;
use App\Http\Controllers\Superadmin\ProdiController;
use App\Http\Controllers\Superadmin\TahunAkademikController;
use App\Http\Controllers\Superadmin\ValidasiController;
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
		Route::post('', [MatakuliahController::class, 'store'])->name('store');
		Route::put('', [MatakuliahController::class, 'update'])->name('update');
		Route::delete('', [MatakuliahController::class, 'delete'])->name('delete');
	});

	// Prodi
  Route::group(['prefix' => 'prodi', 'as' => 'prodi.'], function () {
		Route::get('', [ProdiController::class, 'index'])->name('index');
		Route::post('', [ProdiController::class, 'store'])->name('store');
		Route::put('', [ProdiController::class, 'update'])->name('update');
		Route::delete('', [ProdiController::class, 'delete'])->name('delete');
	});
	
	// Tahun Akademik
  Route::group(['prefix' => 'ta', 'as' => 'ta.'], function () {
		Route::get('', [TahunAkademikController::class, 'index'])->name('index');
		Route::post('', [TahunAkademikController::class, 'store'])->name('store');
		Route::put('', [TahunAkademikController::class, 'update'])->name('update');
	});

	// Pengajaran
  Route::group(['prefix' => 'pengajaran', 'as' => 'pengajaran.'], function () {
		Route::get('', [PengajaranController::class, 'index'])->name('index');
		Route::post('', [PengajaranController::class, 'store'])->name('store');
		Route::put('', [PengajaranController::class, 'update'])->name('update');
		Route::delete('', [PengajaranController::class, 'delete'])->name('delete');
		Route::get('/kurikulum', [PengajaranController::class, 'kurikulum'])->name('kurikulum');
		Route::get('/matakuliah', [PengajaranController::class, 'matakuliah'])->name('matakuliah');
		Route::get('/matakuliah-sks', [PengajaranController::class, 'sks'])->name('sks');
		Route::post('/sgas', [PengajaranController::class, 'sgas'])->name('sgas');
		Route::get('/print', [PengajaranController::class, 'print'])->name('print');
		Route::get('/print-ttd', [PengajaranController::class, 'print_ttd'])->name('print_ttd');
	});

	// Validasi
  Route::group(['prefix' => 'validasi', 'as' => 'validasi.'], function () {
		Route::get('', [ValidasiController::class, 'index'])->name('index');
		Route::put('', [ValidasiController::class, 'update'])->name('update');
		Route::put('/bulk-update', [ValidasiController::class, 'update_all'])->name('update_all');
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

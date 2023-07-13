<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Control\AkunController;
use App\Http\Controllers\Master\DosenController;
use App\Http\Controllers\Master\MatakuliahController;
use App\Http\Controllers\Master\ProdiController;
use App\Http\Controllers\Master\TahunAkademikController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Transaction\PengajaranController;
use App\Http\Controllers\Transaction\ValidasiController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
	return redirect(route('login'));
});

Auth::routes(['register' => false]);

// Akun
Route::group(['prefix' => 'akun', 'as' => 'akun.', 'middleware' => ['auth']], function () {
	Route::put('', [DashboardController::class, 'update'])->name('update');
});

// Superadmin
Route::group(['prefix' => 'superadmin', 'as' => 'superadmin.', 'middleware' => ['role:superadmin', 'auth']], function () {
  
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

// Admin
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['role:admin', 'auth']], function () {
  
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
});

// Prodi
Route::group(['prefix' => 'prodi', 'as' => 'prodi.', 'middleware' => ['role:prodi', 'auth']], function () {
  
	// Dashboard
	Route::get('', [DashboardController::class, 'index'])->name('index');

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
});

// MWI
Route::group(['prefix' => 'mwi', 'as' => 'mwi.', 'middleware' => ['role:mwi', 'auth']], function () {
  
	// Dashboard
	Route::get('', [DashboardController::class, 'index'])->name('index');

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
});

// BSDM
Route::group(['prefix' => 'bsdm', 'as' => 'bsdm.', 'middleware' => ['role:bsdm', 'auth']], function () {
  
	// Dashboard
	Route::get('', [DashboardController::class, 'index'])->name('index');

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
});

// User
Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['role:user', 'auth']], function () {
  
	// Dashboard
	Route::get('', [DashboardController::class, 'index'])->name('index');

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
});

// Report
Route::group(['prefix' => 'report', 'as' => 'report.', 'middleware' => ['role:superadmin|admin|prodi|mwi|bsdm', 'auth']], function () {

	// Matakuliah
	Route::get('/matakuliah', [ReportController::class, 'matakuliah'])->name('matakuliah');

	// Dosen
	Route::get('/dosen', [ReportController::class, 'dosen'])->name('dosen');

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

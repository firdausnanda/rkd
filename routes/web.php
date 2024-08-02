<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Control\AkunController;
use App\Http\Controllers\Master\DosenController;
use App\Http\Controllers\Master\MatakuliahController;
use App\Http\Controllers\Master\ProdiController;
use App\Http\Controllers\Master\TahunAkademikController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Transaction\PembimbinganMahasiswaController;
use App\Http\Controllers\Transaction\PembimbinganTAController;
use App\Http\Controllers\Transaction\PengajaranController;
use App\Http\Controllers\Transaction\PKLController;
use App\Http\Controllers\Transaction\ValidasiController;
use Illuminate\Support\Facades\Artisan;
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
		Route::put('/aktif', [TahunAkademikController::class, 'aktif'])->name('aktif');
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

	// Pembimbingan Mahasiswa
	Route::group(['prefix' => 'pa', 'as' => 'pa.'], function () {
		Route::get('', [PembimbinganMahasiswaController::class, 'index'])->name('index');
		Route::post('', [PembimbinganMahasiswaController::class, 'store'])->name('store');
		Route::put('', [PembimbinganMahasiswaController::class, 'update'])->name('update');
		Route::delete('', [PembimbinganMahasiswaController::class, 'delete'])->name('delete');
		Route::post('/sgas', [PembimbinganMahasiswaController::class, 'sgas'])->name('sgas');
		Route::get('/print', [PembimbinganMahasiswaController::class, 'print'])->name('print');
		Route::get('/print-all', [PembimbinganMahasiswaController::class, 'print_all'])->name('print_ttd');
	});

	// Pembimbingan Tugas Akhir
	Route::group(['prefix' => 'tugas-akhir', 'as' => 'tugas-akhir.'], function () {
		Route::get('', [PembimbinganTAController::class, 'index'])->name('index');
		Route::post('', [PembimbinganTAController::class, 'store'])->name('store');
		Route::put('', [PembimbinganTAController::class, 'update'])->name('update');
		Route::delete('', [PembimbinganTAController::class, 'delete'])->name('delete');
		Route::post('/sgas', [PembimbinganTAController::class, 'sgas'])->name('sgas');
		Route::get('/print', [PembimbinganTAController::class, 'print'])->name('print');
		Route::get('/print-all', [PembimbinganTAController::class, 'print_all'])->name('print_ttd');
	});
	
	// Pembimbingan PKL
	Route::group(['prefix' => 'pkl', 'as' => 'pkl.'], function () {
		Route::get('', [PKLController::class, 'index'])->name('index');
		Route::post('', [PKLController::class, 'store'])->name('store');
		Route::put('', [PKLController::class, 'update'])->name('update');
		Route::delete('', [PKLController::class, 'delete'])->name('delete');
		Route::post('/sgas', [PKLController::class, 'sgas'])->name('sgas');
		Route::get('/print-all', [PKLController::class, 'print_all'])->name('print_ttd');
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
		Route::put('/aktif', [TahunAkademikController::class, 'aktif'])->name('aktif');
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

	// Matakuliah
	Route::group(['prefix' => 'matakuliah', 'as' => 'matakuliah.'], function () {
		Route::get('', [MatakuliahController::class, 'index'])->name('index');
		Route::post('', [MatakuliahController::class, 'store'])->name('store');
		Route::put('', [MatakuliahController::class, 'update'])->name('update');
		Route::delete('', [MatakuliahController::class, 'delete'])->name('delete');
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

	// Pembimbingan Mahasiswa
	Route::group(['prefix' => 'pa', 'as' => 'pa.'], function () {
		Route::get('', [PembimbinganMahasiswaController::class, 'index'])->name('index');
		Route::post('', [PembimbinganMahasiswaController::class, 'store'])->name('store');
		Route::put('', [PembimbinganMahasiswaController::class, 'update'])->name('update');
		Route::delete('', [PembimbinganMahasiswaController::class, 'delete'])->name('delete');
		Route::post('/sgas', [PembimbinganMahasiswaController::class, 'sgas'])->name('sgas');
		Route::get('/print', [PembimbinganMahasiswaController::class, 'print'])->name('print');
		Route::get('/print-all', [PembimbinganMahasiswaController::class, 'print_all'])->name('print_ttd');
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
		Route::put('/aktif', [TahunAkademikController::class, 'aktif'])->name('aktif');
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

	// Pembimbingan Mahasiswa
	Route::group(['prefix' => 'pa', 'as' => 'pa.'], function () {
		Route::get('', [PembimbinganMahasiswaController::class, 'index'])->name('index');
		Route::post('', [PembimbinganMahasiswaController::class, 'store'])->name('store');
		Route::put('', [PembimbinganMahasiswaController::class, 'update'])->name('update');
		Route::delete('', [PembimbinganMahasiswaController::class, 'delete'])->name('delete');
		Route::post('/sgas', [PembimbinganMahasiswaController::class, 'sgas'])->name('sgas');
		Route::get('/print', [PembimbinganMahasiswaController::class, 'print'])->name('print');
		Route::get('/print-all', [PembimbinganMahasiswaController::class, 'print_all'])->name('print_ttd');
	});
	
	// Validasi
	Route::group(['prefix' => 'validasi', 'as' => 'validasi.'], function () {
		Route::get('', [ValidasiController::class, 'index'])->name('index');
		Route::put('', [ValidasiController::class, 'update'])->name('update');
		Route::put('/bulk-update', [ValidasiController::class, 'update_all'])->name('update_all');
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

	// Pembimbingan Mahasiswa
	Route::group(['prefix' => 'pa', 'as' => 'pa.'], function () {
		Route::get('', [PembimbinganMahasiswaController::class, 'index'])->name('index');
		Route::post('', [PembimbinganMahasiswaController::class, 'store'])->name('store');
		Route::put('', [PembimbinganMahasiswaController::class, 'update'])->name('update');
		Route::delete('', [PembimbinganMahasiswaController::class, 'delete'])->name('delete');
		Route::post('/sgas', [PembimbinganMahasiswaController::class, 'sgas'])->name('sgas');
		Route::get('/print', [PembimbinganMahasiswaController::class, 'print'])->name('print');
		Route::get('/print-all', [PembimbinganMahasiswaController::class, 'print_all'])->name('print_ttd');
	});
});

// BAA
Route::group(['prefix' => 'baa', 'as' => 'baa.', 'middleware' => ['role:baa', 'auth']], function () {

	// Dashboard
	Route::get('', [DashboardController::class, 'index'])->name('index');
});

// Report
Route::group(['prefix' => 'report', 'as' => 'report.', 'middleware' => ['role:superadmin|admin|prodi|mwi|bsdm|baa', 'auth']], function () {

	// Matakuliah
	Route::get('/matakuliah', [ReportController::class, 'matakuliah'])->name('matakuliah');

	// Dosen
	Route::get('/dosen', [ReportController::class, 'dosen'])->name('dosen');
	Route::post('/dosen/print', [ReportController::class, 'printDosen'])->name('printDosen');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

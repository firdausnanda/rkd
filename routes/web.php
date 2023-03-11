<?php

use App\Http\Controllers\Superadmin\AkunController;
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
     
    // Akun
    Route::group(['prefix' => 'akun', 'as' => 'akun.'], function () {
		Route::get('', [AkunController::class, 'index'])->name('index');
		Route::post('', [AkunController::class, 'store'])->name('store');
		Route::put('', [AkunController::class, 'update'])->name('update');
	});
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

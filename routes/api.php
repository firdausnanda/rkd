<?php

use App\Http\Controllers\Api\SiakadController;
use App\Http\Controllers\Transaction\PengajaranController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Siakad
Route::group(['prefix' => 'siakad', 'as' => 'siakad.'], function () {
    Route::get('', [SiakadController::class, 'index'])->name('index');
    Route::get('/print', [PengajaranController::class, 'print'])->name('print');
    Route::get('/print-ttd', [PengajaranController::class, 'print_ttd'])->name('print_ttd');
});

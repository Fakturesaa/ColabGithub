<?php

use App\Http\Controllers\EkskulController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PembinaController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('ekskul.index');
});

Route::resource('ekskul', EkskulController::class);
Route::resource('pendaftaran', PendaftaranController::class);
Route::resource('pembina', PembinaController::class);
Route::resource('siswa', SiswaController::class);

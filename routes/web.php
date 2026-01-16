<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [LaporanController::class, 'index']);
Route::get('/download-database', [LaporanController::class, 'downloadDatabase']);
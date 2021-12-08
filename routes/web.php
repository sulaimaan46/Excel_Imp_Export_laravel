<?php

use App\Http\Controllers\ExcelDataController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/file-upload', [ExcelDataController::class, 'index'])->name('file_upload');

Route::post('file-upload', [ExcelDataController::class, 'fileUploadPost'])->name('file.upload.post');

Route::get('/file_read/{type}/{header}', [ExcelDataController::class, 'fileExport']);

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

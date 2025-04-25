<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\TransactionExportController;



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

Route::get('/unauthorized', function () {
    return response()->json([
        'error' => 401,
        'message' => 'un Authorized'
    ]);
})->name('unauthorized');

Route::get('/{filename}.txt', [FileDownloadController::class, 'download']);


Route::get('/export-transactions', [TransactionExportController::class, 'export'])->name('transactions.export');


//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

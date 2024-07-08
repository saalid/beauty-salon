<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Filament\Resources\CategoryResource\Api\CategoryApiService;
use App\Filament\Resources\ProductResource\Api\ProductApiService;
use App\Filament\Resources\TeacherResource\Api\TeacherApiService;
use App\Filament\Resources\CartResource\Api\CartApiService;
use \Kavenegar as kv;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CartApiController;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\OrderApiController;

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
//UserApiService::routes();
CategoryApiService::routes();
ProductApiService::routes();
TeacherApiService::routes();
CartApiService::routes();

Route::controller(AuthController::class)->group(function () {

    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('login', 'login');
    Route::post('loginVerify', 'loginVerify');
    Route::get('getUser', 'getUser');
    Route::post('getLicense', 'getLicense');
    Route::post('register', 'register');

});

Route::controller(CartApiController::class)->prefix('cart')->group(function ()
{
    Route::post('add', 'add');
    Route::post('remove', 'remove');
    Route::get('list', 'list');
});

Route::post('/order/create', [OrderApiController::class, 'createOrder'])->name('order.create');
Route::get('/purchase/{order}', [PaymentApiController::class, 'purchase'])->name('purchase');
Route::get('/verify', [PaymentApiController::class, 'verify'])->name('verify');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test-gateway', function () {

// Do all things together in a single line.
    return Payment::purchase(
        (new Invoice)->amount(1000.00),
        function($driver, $transactionId) {


            // Store transactionId in database.
            // We need the transactionId to verify payment in the future.
        }
    )->pay()->render();

//// Retrieve json format of Redirection (in this case you can handle redirection to bank gateway)
//    return Payment::purchase(
//        (new Invoice)->amount(1000),
//        function($driver, $transactionId) {
//            // Store transactionId in database.
//            // We need the transactionId to verify payment in the future.
//        }
//    )->pay()->toJson();
})->name('test.gateway');


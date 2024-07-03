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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


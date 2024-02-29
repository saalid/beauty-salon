<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Filament\Resources\CategoryResource\Api\CategoryApiService;
use App\Filament\Resources\ProductResource\Api\ProductApiService;
use App\Filament\Resources\TeacherResource\Api\TeacherApiService;
//use App\Filament\Resources\UserResource\Api\UserApiService;
use App\Filament\Resources\UserResource\Api\UserApiService;
use \Kavenegar as kv;
use App\Http\Controllers\AuthController;

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
UserApiService::routes();
CategoryApiService::routes();
ProductApiService::routes();
TeacherApiService::routes();

Route::controller(AuthController::class)->group(function () {

    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('login', 'login');
    Route::post('loginVerify', 'loginVerify');
    Route::get('getUser', 'getUser');
    Route::post('register', 'register');

});

Route::get('/test-sms', function (Request $request)
{
    try{
        $receptor = "09387789518";
        $token = "123";
        $token2 = null;
        $token3 = null;
        $template="verify";
        //Send null for tokens not defined in the template
        //Pass token10 and token20 as parameter 6th and 7th
        $result = kv::VerifyLookup($receptor, $token, $token2, $token3, $template, $type = null);
        if($result){
            foreach($result as $r){
                echo "messageid = $r->messageid";
                echo "message = $r->message";
                echo "status = $r->status";
                echo "statustext = $r->statustext";
                echo "sender = $r->sender";
                echo "receptor = $r->receptor";
                echo "date = $r->date";
                echo "cost = $r->cost";
            }
        }
    }
    catch(\Kavenegar\Exceptions\ApiException $e){
        // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
        echo $e->errorMessage();
    }
    catch(\Kavenegar\Exceptions\HttpException $e){
        // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
        echo $e->errorMessage();
    }

});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

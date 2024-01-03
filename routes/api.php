<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Filament\Resources\CategoryResource\Api\CategoryApiService;
use App\Filament\Resources\ProductResource\Api\ProductApiService;
use App\Filament\Resources\TeacherResource\Api\TeacherApiService;
//use App\Filament\Resources\UserResource\Api\UserApiService;
use App\Filament\Resources\UserResource\Api\UserApiService;
use \Kavenegar as kv;

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

Route::get('/test-sms', function (Request $request)
{
    try{
        $sender = "10008663";		//This is the Sender number

        $message = "خدمات پیام کوتاه کاوه نگار";		//The body of SMS

        $receptor = array("09054476441");			//Receptors numbers

        $result = kv::Send($sender,$receptor,$message);
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
    }catch(\Exceptions $ex){
        // در صورت بروز خطایی دیگر
        echo $ex->getMessage();
}

});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

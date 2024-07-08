<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\UserBoughtLicense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Kavenegar;
class UserProducts extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function list()
    {
        $userId = auth()->user()->id;
        $data = [];
        $userProducts = UserBoughtLicense::where('user_id', $userId)->get();

        foreach ($userProducts as $userProduct){
            $data [] = [
              'infoLicense' => $userProduct,
              'product' => Product::where('id', $userProduct->product_id)->first(),
            ];
        }
        if(count($data) === 0 ){
            return [
                'message' => 'User dose not have product'
            ];
        }
        return $data;
    }
}

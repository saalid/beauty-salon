<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Kavenegar;
class CartApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function add(Request $request)
    {
        $matchThese = ['user_id'=>auth()->user()->id];
        $cart = Cart::updateOrCreate($matchThese,['sum'=>$request->sum]);
        if (CartItem::where([
            ['cart_id', '=', $cart->id],
            ['product_id', '=', $request->product_id]
        ])->exists()) {
            return ['status' => 'Item Exist'];
        }
        $cartItem = CartItem::create(['cart_id' => $cart->id, 'product_id' => $request->product_id]);
        return [
            "status" => true,
            "cart_item" => Product::find($cartItem->product_id)
        ];
    }
}

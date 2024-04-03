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
            ['product_id', '=', $request->productId]
        ])->exists()) {
            return ['message' => 'Item Exist'];
        }
        $cartItem = CartItem::create(['cart_id' => $cart->id, 'product_id' => $request->productId]);
        return [
            "status" => true,
            "cart_item" => [
                "cartItemId" => $cartItem->id,
                "info" => Product::find($cartItem->product_id)
            ]
        ];
    }

    public function remove(Request $request)
    {
        $matchThese = ['user_id'=>auth()->user()->id];
        Cart::updateOrCreate($matchThese,['sum'=>$request->sum]);

        if(CartItem::where('id', $request->cartItemId)->delete() === 0)
        {
            return [
                "message" => "Item Not Exist"
            ];
        }

        return [
            "status" => true
        ];
    }
}

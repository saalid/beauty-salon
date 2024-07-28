<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\UserBoughtLicense;
use App\Services\UserBoughtLicenseService;
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
        $matchThese = [
            'user_id' => auth()->user()->id
        ];
        $cart = Cart::where('user_id', auth()->user()->id)->first();
        $product = Product::where('id', $request->productId)->first();

        $userProducts = UserBoughtLicense::where([
            ['user_id', '=', auth()->user()->id],
            ['product_id', '=', $request->productId]
        ]);
        if($userProducts->count > 0)
        {
            return [
                'message' => 'شما قبلا این دوره را خریداری کرده اید'
            ];
        }

        $sum = $cart->sum + $product->price;

        $cart = Cart::updateOrCreate($matchThese,['sum'=>$sum]);
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
        $cart = Cart::where('user_id', auth()->user()->id)->first();
        $product = Product::where('id', $request->productId)->first();
        $cartItem = CartItem::where([
            ['cart_id', '=', $cart->id],
            ['product_id', '=', $request->productId]
        ]);

        if($cartItem->count() > 0)
        {
            $sum = $cart->sum - $product->price;
            $cart = Cart::updateOrCreate($matchThese,['sum'=>$sum]);
            if(CartItem::where([
                    ['cart_id', '=', $cart->id],
                    ['product_id', '=', $request->productId]
                ])->delete() === 0)
            {
                return [
                    "message" => "Item Not Exist"
                ];
            }

            return [
                "status" => true
            ];
        }else{
            return [
                "message" => "Cart is empty"
            ];
        }

    }

    public function list(Request $request)
    {
        $infoProduct = [];
        $cart = Cart::where('user_id', auth()->user()->id)->first();
        $cartItems = CartItem::where('cart_id', $cart->id)->get();

        foreach ($cartItems as $cartItem)
        {
            $infoProduct [] = Product::with('teacher')->find($cartItem->product_id);
        }
        $data = [
            'count' => $cartItems->count(),
            'sum' => $cart->sum,
            'items' => $infoProduct
        ];
        return $data;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderApiController extends Controller
{
    private $userId;
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->userId = auth()->user()->id;
    }

    public function createOrder()
    {
        // Create the order
        $cart = Cart::where('user_id', auth()->user()->id)->first();

        $cartItems = CartItem::where('cart_id', $cart->id)->get();



        $order = Order::create([
            'user_id' => $this->userId,
            'price' => $cart->sum,
            'status' => 'pending', // Initial status
        ]);

        foreach ($cartItems as $cartItem)
        {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $cartItem->product_id;
            $orderItem->price = Product::where('id', $cartItem->product_id)->first()->price;

            $orderItem->save();
        }

        // Redirect to the purchase route with the order ID
        return redirect()->route('purchase', ['order' => $order->id]);
    }



}

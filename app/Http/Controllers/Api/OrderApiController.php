<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;

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

        $order = Order::create([
            'user_id' => $this->userId,
            'price' => $cart->sum,
            'status' => 'pending', // Initial status
        ]);

        // Redirect to the purchase route with the order ID
        return redirect()->route('purchase', ['order' => $order->id]);
    }



}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;

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
        $cart = Cart::where('user_id', $this->userId)->first();

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

        return [
            'orderId' => $order->id
        ];
    }

    public function list()
    {
        $data = [];
        $userOrders = Order::where([
            'user_id' => $this->userId,
            'status' => 'completed'

        ])->get();

        $key = 0;
        foreach ($userOrders as $userOrder) {
            $orderItems = OrderItem::where('order_id', $userOrder->id)->get();
            foreach ($orderItems as $orderItem) {
                $transaction = Transaction::where('transaction_id', $userOrder->id)->first();
                $data ['order-'.$userOrder->id]['price'] = $userOrder->price;
                $data ['order-'.$userOrder->id]['codeTransaction'] = base64_encode($transaction->user_id . ':' . $userOrder->id);
                $data ['order-'.$userOrder->id]['items'][] = $orderItem;
                $data ['order-'.$userOrder->id]['items'][$key]['productInfo'] = Product::where('id', $orderItem->product_id)->first();
                $key++;
            }
        }

        return $data;
    }
}

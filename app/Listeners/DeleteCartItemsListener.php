<?php

namespace App\Listeners;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use App\Services\Kavenegar;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderVerified;

class DeleteCartItemsListener
{


    /**
     * Handle the event.
     */
    public function handle(OrderVerified $event): void
    {
        $order = $event->order;

        $cart  = Cart::where('user_id', $order->user_id)->first();

        $cart->sum = 0;
        $cart->update();

        CartItem::where('cart_id', $cart->id)->delete();

    }
}

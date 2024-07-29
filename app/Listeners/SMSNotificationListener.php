<?php

namespace App\Listeners;

use App\Models\User;
use App\Services\Kavenegar;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderVerified;

class SMSNotificationListener
{


    /**
     * Handle the event.
     */
    public function handle(OrderVerified $event): void
    {
        $order = $event->order;

        $user  = User::where('id', $order->user_id)->first();

        if(config('app.mode') === "production") {
            (new Kavenegar)->sendOtp($user->phone, "http://neginzare.com/login", 'welcome');
        }
    }
}

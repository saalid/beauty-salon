<?php

namespace App\Listeners;

use App\Models\OrderItem;
use App\Models\UserBoughtLicense;
use App\Services\SpotPlayerService;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\OrderVerified;

class StudentProductAdditionListener
{

    /**
     * Handle the event.
     */
    public function handle(OrderVerified $event): void
    {
        $order = $event->order;

        $orderItems = OrderItem::where('order_id', $order->id)->get();

        foreach ($orderItems as $orderItem)
        {
            $userProducts = UserBoughtLicense::where('product_id', $orderItem->product_id)->count();

            if($userProducts === 0)
            {
                $user = User::where('id', $order->user_id)->first();
                $infoLicense = SpotPlayerService::getLicense($user->phone, $orderItem->product_id);

                UserBoughtLicense::create([
                    'user_id' => $order->user_id,
                    'product_id' => $orderItem->product_id,
                    'license_id' => $infoLicense['id'],
                    'license_key' => $infoLicense['key'],
                    'url_download' => $infoLicense['url']
                ]);
            }

        }
    }
}

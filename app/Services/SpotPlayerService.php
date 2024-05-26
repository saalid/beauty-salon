<?php

namespace App\Services;

use App\Services\ProductService;
use App\Services\UserService;

class SpotPlayerService
{

    public static function getLicense($receiverPhone, $productId)
    {
        $device = spotplayer()->setDevice(
            $numberOfAllowedActiveDevices=2,
            $Windows=1,
            $MacOS=1,
            $Ubuntu=1,
            $Android=1,
            $IOS=1,
            $WebApp=2
        );

// Generating a license
        $watermarks = [
            "texts" => [
                ["text" => $receiverPhone]
            ]
        ];

        $licence = spotplayer()->licence(
            $name=(new UserService)->getName($receiverPhone),
            $courses=[(new ProductService)->getSpotPlayerId($productId)],
            $watermarks,
            $device,
            $payload='payload'
        );

        return [
            "id" => $licence['_id'],
            "key" => $licence['key'],
            "url" => $licence['url']
        ];
    }
}

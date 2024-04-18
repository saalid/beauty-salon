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
            $Windows=0,
            $MacOS=0,
            $Ubuntu=0,
            $Android=0,
            $IOS=0,
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

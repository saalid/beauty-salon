<?php

namespace App\Services;

use App\Models\Product;
class ProductService
{

    public function getSpotPlayerId($productId)
    {
        return Product::where('id', $productId)->first()->spot_player_id;
    }
}

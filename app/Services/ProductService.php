<?php

namespace App\Services;

use App\Models\Product;
class ProductService
{

    public function getSpotPlayerId($productId)
    {
        return Product::find($productId)->spot_player_id;
    }
}

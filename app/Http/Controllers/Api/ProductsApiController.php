<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Models\UserBoughtLicense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Kavenegar;
class ProductsApiController extends Controller
{



    public function getBySlug($slug)
    {
        return Product::with('teacher')->where('slug', $slug)->first();
    }
}

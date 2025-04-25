<?php
namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Models\Cart;
use App\Models\CartItem;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class CartCleanerController extends Controller
{
    public function execute(): \Illuminate\Http\RedirectResponse
    {
        Cart::query()->update(['sum' => 0]);

        CartItem::query()->delete();

        return redirect()->route('filament.admin.pages.dashboard')->with('success', 'All carts and cart items have been cleared successfully.');
    }
}

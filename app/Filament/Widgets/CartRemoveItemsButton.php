<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Forms\Components\Button;
use Illuminate\Support\Facades\URL;

class CartRemoveItemsButton extends Widget
{
    protected static string $view = 'filament.widgets.cart-remove-button';

    protected int|string|array $columnSpan = 'full';
}


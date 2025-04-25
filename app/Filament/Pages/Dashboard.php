<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CartRemoveItemsButton;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\TransactionReportButton;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return [
            TransactionReportButton::class,
            CartRemoveItemsButton::class
        ];
    }
}

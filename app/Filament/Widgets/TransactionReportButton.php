<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Forms\Components\Button;
use Illuminate\Support\Facades\URL;

class TransactionReportButton extends Widget
{
    protected static string $view = 'filament.widgets.transaction-report-button';

    protected int|string|array $columnSpan = 'full';
}


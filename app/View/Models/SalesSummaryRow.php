<?php

namespace App\View\Models;

use Illuminate\Database\Eloquent\Model;

class SalesSummaryRow extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    // Disable actual table usage
    public function getTable(): string
    {
        return 'sales_summary_rows';
    }
}

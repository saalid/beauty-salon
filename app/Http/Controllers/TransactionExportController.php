<?php
namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class TransactionExportController extends Controller
{
    public function export(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(
            new TransactionsExport,
            'transactions-' . Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }
}

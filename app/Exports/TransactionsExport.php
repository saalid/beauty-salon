<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class TransactionsExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        // Join with the Student table, filter by 'paid' transactions, and select necessary fields
        return Transaction::select(
            'users.name',
            'users.email',
            'users.phone',
            'transactions.amount',
            'transactions.status',
            'transactions.created_at'
        )
            ->join('users', 'users.id', '=', 'transactions.user_id') // Adjust based on your relationship
            ->where('transactions.status', 'paid') // Filter only paid transactions
            ->get();
    }

    public function headings(): array
    {
        return ['Name Family', 'Email', 'Phone', 'Amount', 'Status', 'Created At'];
    }

    // Add the total sum to the last row of the sheet
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Get the total amount
                $totalAmount = Transaction::where('status', 'paid')->sum('amount');

                // Add the total sum in the last row (below the data)
                $lastRow = $event->sheet->getHighestRow() + 1;

                // Set the total amount in the last row
                $event->sheet->setCellValue('D' . $lastRow, 'Total Amount');
                $event->sheet->setCellValue('E' . $lastRow, $totalAmount);

                // Optionally, apply bold formatting to the total row
                $event->sheet->getStyle('D' . $lastRow . ':E' . $lastRow)->getFont()->setBold(true);
            },
        ];
    }
}

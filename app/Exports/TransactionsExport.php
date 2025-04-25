<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
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

}

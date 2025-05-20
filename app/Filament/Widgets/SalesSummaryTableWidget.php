<?php

namespace App\Filament\Widgets;

use App\Models\UserBoughtLicense;
use App\View\Models\SalesSummaryRow;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Morilog\Jalali\Jalalian;
use Filament\Forms;


class SalesSummaryTableWidget extends TableWidget
{
    protected static ?string $heading = 'گزارش فروش روزانه دوره‌ها';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 20;
    public ?string $daysFilter = '7';


    private static array $fakeRecords = [];

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('daysFilter')
                ->label('بازه زمانی')
                ->options([
                    '7' => '7 روز اخیر',
                    '14' => '14 روز اخیر',
                    '30' => '30 روز اخیر',
                    '60' => '60 روز اخیر',
                ])
                ->default('7')
                ->reactive()
                ->afterStateUpdated(fn () => $this->resetTableRecords()), // Refresh table
        ];
    }

    protected function getTableQuery(): Builder
    {
        // ✅ Generate fake records (will be used in getTableColumns)
        self::$fakeRecords = $this->generateFakeSalesData();

        // ✅ Return an empty query builder just to satisfy Filament
        return SalesSummaryRow::query()->whereRaw('0 = 1');
    }

    public function getTableRecordKey($record): string
    {
        return md5($record->date . '-' . $record->course_title);
    }

    protected function generateFakeSalesData(): array
    {
        $days = (int) ($this->daysFilter ?? 7);
        $endDate = Carbon::today();
        $startDate = $endDate->copy()->subDays($days - 1);

        $sales = UserBoughtLicense::query()
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->with('product')
            ->get();

        $grouped = $sales->groupBy([
            fn ($sale) => $sale->created_at->format('Y-m-d'),
            fn ($sale) => $sale->product_id,
        ]);

        $records = [];

        foreach ($grouped as $date => $products) {
            foreach ($products as $productId => $items) {
                $records[] = new SalesSummaryRow([
                    'date' => $date,
                    'course_title' => optional($items->first()->product)->title ?? 'نامشخص',
                    'count' => $items->count(),
                ]);
            }
        }

        return collect($records)->sortByDesc('date')->values()->all();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('date')
                ->label('تاریخ')
                ->formatStateUsing(fn ($state) => Jalalian::fromCarbon(Carbon::parse($state))->format('Y/m/d')),

            Tables\Columns\TextColumn::make('course_title')
                ->label('نام دوره'),

            Tables\Columns\TextColumn::make('count')
                ->label('تعداد فروش'),
        ];
    }

    public function getTableRecords(): \Illuminate\Database\Eloquent\Collection
    {
        return new \Illuminate\Database\Eloquent\Collection(self::$fakeRecords);
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\UserBoughtLicense;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Morilog\Jalali\Jalalian;


class BlogPostsChart extends ChartWidget
{
    protected static ?string $heading = 'آمار فروش روزانه دوره ها';// number of columns the widget spans (1-3)


    protected function getData(): array
    {
        // 1. Define the date range (last 7 days as example)
        $endDate = Carbon::today();
        $startDate = $endDate->copy()->subDays(29);

        // 2. Get all relevant sales in the date range, with product info
        $sales = UserBoughtLicense::query()
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->with('product') // eager load product relationship
            ->get();

        // 3. Group sales by date and product_id
        $grouped = $sales->groupBy(function ($sale) {
            return $sale->created_at->format('Y-m-d'); // group by date string
        });

        // 4. Collect all products involved
        $products = $sales->pluck('product')->unique('id')->keyBy('id');

        // 5. Prepare labels array (dates)
        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        // 6. Build datasets for each product
        $datasets = [];

        foreach ($products as $productId => $product) {
            $data = [];

            foreach ($dates as $date) {
                // Count sales for this product on this date
                $count = isset($grouped[$date])
                    ? $grouped[$date]->where('product_id', $productId)->count()
                    : 0;
                $data[] = $count;
            }

            $datasets[] = [
                'label' => $product->title,
                'data' => $data,
            ];
        }

        // 7. Format dates for label display (optionally convert to Jalali if needed)
//        $labels = array_map(fn($d) => Carbon::parse($d)->format('Y/m/d'), $dates);
        $labels = array_map(fn($d) => Jalalian::fromCarbon(Carbon::parse($d))->format('Y/m/d'), $dates);

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}

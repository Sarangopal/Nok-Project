<?php

namespace App\Filament\Widgets;

use App\Models\VerificationAttempt;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VerificationAttemptsChart extends ChartWidget
{
    protected ?string $heading = 'Verification Attempts (14 days)';
    
    protected int|string|array $columnSpan = 'full';
     // ✅ Correct syntax for column span (optional)
     protected static ?int $sort = 4;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $days = collect(range(13, 0))->map(fn ($i) => now()->subDays($i)->startOfDay());
        $labels = $days->map(fn ($d) => $d->format('M d'))->all();

        $cacheKey = 'dash:verify_chart_14d';
        [$success, $failed] = Cache::remember($cacheKey, 60, function () use ($days) {
            $from = $days->first();
            $to = $days->last()->copy()->endOfDay();

            $rows = VerificationAttempt::selectRaw('DATE(created_at) as d, was_successful, COUNT(*) as c')
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('d', 'was_successful')
                ->get();

            $byDate = [];
            foreach ($rows as $row) {
                $byDate[$row->d][(int) $row->was_successful] = (int) $row->c;
            }

            $succ = [];
            $fail = [];
            foreach ($days as $day) {
                $key = $day->toDateString();
                $succ[] = $byDate[$key][1] ?? 0;
                $fail[] = $byDate[$key][0] ?? 0;
            }
            return [$succ, $fail];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Success',
                    'data' => $success,
                    'backgroundColor' => '#22c55e',
                ],
                [
                    'label' => 'Failed',
                    'data' => $failed,
                    'backgroundColor' => '#ef4444',
                ],
            ],
            'labels' => $labels,
        ];
    }
}



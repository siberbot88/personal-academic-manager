<?php

namespace App\Services;

use App\Models\StudySession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudyStatsService
{
    /**
     * Count sessions in a specific week.
     */
    public function weeklyCount(?Carbon $weekStart = null): int
    {
        $start = $weekStart ? $weekStart->copy()->startOfWeek() : now()->startOfWeek();
        $end = $start->copy()->endOfWeek();

        return StudySession::where('user_id', auth()->id())
            ->whereBetween('started_at', [$start, $end])
            ->count();
    }
    public function weeklyTotalMinutes(?Carbon $weekStart = null): int
    {
        $start = $weekStart ? $weekStart->copy()->startOfWeek() : now()->startOfWeek();
        $end = $start->copy()->endOfWeek();

        return (int) StudySession::where('user_id', auth()->id())
            ->whereBetween('started_at', [$start, $end])
            ->sum('duration_min');
    }

    /**
     * Calculate current daily streak.
     * Streak = consecutive days ending today or yesterday where user had at least 1 session.
     */
    public function currentDailyStreak(): int
    {
        $userId = auth()->id();

        // Get all dates where user had sessions, ordered descending
        // We group by date to check unique days
        $dates = StudySession::where('user_id', $userId)
            ->select(DB::raw('DATE(started_at) as date'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->toArray();

        if (empty($dates)) {
            return 0;
        }

        $streak = 0;
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        // Check if streak is active (has entry today or yesterday)
        $latestDate = $dates[0];
        if ($latestDate !== $today && $latestDate !== $yesterday) {
            return 0;
        }

        // Iterate backwards
        $currentDate = Carbon::parse($latestDate);

        foreach ($dates as $dateStr) {
            $date = Carbon::parse($dateStr);

            // If this date matches expected current date in sequence
            if ($date->format('Y-m-d') === $currentDate->format('Y-m-d')) {
                $streak++;
                $currentDate->subDay(); // Expect next date to be previous day
            } else {
                // Gap found
                break;
            }
        }

        return $streak;
    }

    /**
     * Get progress towards weekly target (default 5).
     */
    public function progressToWeeklyTarget(int $target = 5): array
    {
        $count = $this->weeklyCount();
        $remaining = max(0, $target - $count);

        return [
            'count' => $count,
            'target' => $target,
            'remaining' => $remaining,
            'achieved' => $remaining === 0,
        ];
    }
}

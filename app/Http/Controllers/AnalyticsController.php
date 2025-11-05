<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Reminder;
use App\Models\Task;
use App\Models\TaskLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function weekly(Request $request)
    {
        $userId = Auth::id();
        $week = $request->query('week', 'this'); // this | last

        $now = Carbon::now();
        $start = $week === 'last'
            ? (clone $now)->startOfWeek(Carbon::MONDAY)->subWeek()
            : (clone $now)->startOfWeek(Carbon::MONDAY);
        $end = (clone $start)->copy()->endOfWeek(Carbon::SUNDAY);

        $cacheKey = "weekly_analytics:{$userId}:{$start->toDateString()}_{$end->toDateString()}";

        $data = Cache::remember($cacheKey, 600, function () use ($userId, $start, $end) {
            // Build 7-day map
            $period = [];
            $cursor = $start->copy();
            while ($cursor <= $end) {
                $period[$cursor->toDateString()] = 0;
                $cursor->addDay();
            }

            $buildSeries = function ($rows, $dateColumn) use ($period) {
                $map = $period; // copy keys
                foreach ($rows as $row) {
                    $date = Carbon::parse($row->{$dateColumn})->toDateString();
                    if (isset($map[$date])) {
                        $map[$date] = (int) ($row->cnt ?? 0);
                    }
                }
                return array_values($map);
            };

            // Tasks created per day
            $tasksCreated = Task::query()
                ->select(DB::raw('DATE(created_at) as d'), DB::raw('COUNT(*) as cnt'))
                ->where('user_id', $userId)
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('d')
                ->orderBy('d')
                ->get();

            // Tasks completed per day via TaskLog
            $tasksCompleted = TaskLog::query()
                ->select(DB::raw('DATE(done_on) as d'), DB::raw('COUNT(*) as cnt'))
                ->whereBetween('done_on', [$start, $end])
                ->whereHas('task', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->groupBy('d')
                ->orderBy('d')
                ->get();

            // Reminders created per day
            $remindersCreated = Reminder::query()
                ->select(DB::raw('DATE(created_at) as d'), DB::raw('COUNT(*) as cnt'))
                ->where('user_id', $userId)
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('d')
                ->orderBy('d')
                ->get();

            // Notes created per day
            $notesCreated = Note::query()
                ->select(DB::raw('DATE(created_at) as d'), DB::raw('COUNT(*) as cnt'))
                ->where('user_id', $userId)
                ->whereBetween('created_at', [$start, $end])
                ->groupBy('d')
                ->orderBy('d')
                ->get();

            // Labels as ISO date strings for Mon..Sun
            $labels = array_keys($period);

            return [
                'labels' => $labels,
                'series' => [
                    'tasks_created' => $buildSeries($tasksCreated, 'd'),
                    'tasks_completed' => $buildSeries($tasksCompleted, 'd'),
                    'reminders_created' => $buildSeries($remindersCreated, 'd'),
                    'notes_created' => $buildSeries($notesCreated, 'd'),
                ],
                'range' => [
                    'start' => $start->toDateString(),
                    'end' => $end->toDateString(),
                ],
            ];
        });

        return response()->json($data);
    }
}

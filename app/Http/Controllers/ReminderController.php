<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reminder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = Reminder::where('user_id', Auth::id())->latest()->get();
        return view('reminders.index', compact('reminders'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_time' => 'nullable|date',
        ]);

        Reminder::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'reminder_time' => $request->reminder_time,
        ]);

        return redirect()->route('reminders.index')->with('success', 'Reminder added!');
    }

    /**
     * Return reminders that are due now or within the last 5 minutes.
     */
    public function due(Request $request)
    {
        $userId = Auth::id();
        $now = now();
        $windowStart = $now->copy()->subMinutes(15);
        $windowEnd = $now->copy()->addMinutes(1);

        $reminders = Reminder::query()
            ->where('user_id', $userId)
            ->whereNotNull('reminder_time')
            ->whereBetween('reminder_time', [$windowStart, $windowEnd])
            ->orderBy('reminder_time', 'asc')
            ->get(['id','title','description','reminder_time']);

        return response()->json([
            'now' => $now->toIso8601String(),
            'data' => $reminders,
        ]);
    }
}

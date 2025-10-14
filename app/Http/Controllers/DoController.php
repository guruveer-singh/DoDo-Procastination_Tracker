<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->where('is_archived', false)
            ->latest()
            ->get();
            
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $validated['user_id'] = Auth::id();
            Task::create($validated);
            return back()->with('success', 'Task added successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create task: ' . $e->getMessage());
            return back()->with('error', 'Failed to add task. Please try again.');
        }
    }

    public function done(Task $task)
    {
        try {
            DB::beginTransaction();
            $task->markDoneToday();
            DB::commit();

            return response()->json([
                'ok' => true,
                'task' => [
                    'id' => $task->id,
                    'current_streak' => $task->current_streak,
                    'best_streak' => $task->best_streak,
                    'last_completed_date' => optional($task->last_completed_date)->toDateString(),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to mark task as done: ' . $e->getMessage());
            return response()->json([
                'ok' => false,
                'message' => 'Failed to update task status.'
            ], 500);
        }
    }

    public function archive(Task $task)
    {
        try {
            $task->update(['is_archived' => true]);
            return back()->with('success', 'Task archived successfully');
        } catch (\Exception $e) {
            Log::error('Failed to archive task: ' . $e->getMessage());
            return back()->with('error', 'Failed to archive task. Please try again.');
        }
    }

    public function showArchived()
    {
        $archivedTasks = Task::where('user_id', Auth::id())
            ->where('is_archived', true)
            ->latest()
            ->get();

        return view('tasks.archive', compact('archivedTasks'));
    }

    public function restore(Task $task)
    {
        try {
            $task->update(['is_archived' => false]);
            return back()->with('success', 'Task restored successfully');
        } catch (\Exception $e) {
            Log::error('Failed to restore task: ' . $e->getMessage());
            return back()->with('error', 'Failed to restore task. Please try again.');
        }
    }

    public function forceDelete(Task $task)
    {
        try {
            $task->delete();
            return back()->with('success', 'Task permanently deleted');
        } catch (\Exception $e) {
            Log::error('Failed to delete task: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete task. Please try again.');
        }
    }
}
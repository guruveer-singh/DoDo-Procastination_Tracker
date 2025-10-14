@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Add Task Form -->
    <div class="task-form mb-8">
        <form id="new-task-form" action="{{ route('tasks.store') }}" method="POST" class="flex gap-4 mb-8">
            @csrf
            <div class="flex-1">
                <input type="text" name="title" placeholder="What needs to be done?" required 
                       class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-400">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="material-icons-round">add</i> Add Task
            </button>
        </form>
    </div>

    <!-- Tasks List -->
    <div class="tasks-list space-y-4">
        @forelse ($tasks as $task)
            <div class="task bg-white p-4 rounded-lg shadow" id="task-{{ $task->id }}">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="title text-lg font-medium">{{ $task->title }}</div>
                        @if($task->notes)
                            <div class="text-sm text-gray-500 mt-1">{{ $task->notes }}</div>
                        @endif
                        <div class="mt-2 text-sm text-gray-600">
                            <span class="streak">
                                {{ $task->current_streak }} day{{ $task->current_streak == 1 ? '' : 's' }} streak
                            </span>
                            @if($task->best_streak)
                                <span class="text-yellow-600 ml-2">(Best: {{ $task->best_streak }})</span>
                            @endif
                            @if($task->last_completed_date)
                                <div class="text-xs text-gray-500 mt-1">
                                    Last done: {{ $task->last_completed_date->isToday() ? 'Today' : $task->last_completed_date->format('M d, Y') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2">
                        @if($task->completed_at)
                            <span class="text-green-500 flex items-center">
                                <i class="material-icons-round text-xl mr-1">check_circle</i>
                                Done
                            </span>
                        @else
                            <button type="button" 
        data-task-id="{{ $task->id }}"
        class="mark-done-btn btn btn-primary text-sm px-3 py-1">
    <i class="material-icons-round text-base mr-1">check</i> Done
</button>
                        @endif
                        
                        <form action="{{ route('tasks.archive', $task) }}" method="POST" class="inline">
    @csrf
    <button type="submit" class="btn text-sm px-3 py-1" 
            style="background:#fa5c45; color:#1b0e0e;"
            onclick="return confirm('Are you sure you want to archive this task?')">
        <i class="material-icons-round text-base mr-1">archive</i> Archive
    </button>
</form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <p>No tasks yet. Add your first task above!</p>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
    // This will be overridden by custom.js if it loads
    window.markDone = async function(taskId) {
        try {
            const response = await fetch(`/tasks/${taskId}/done`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                window.location.reload();
            } else {
                alert('Failed to mark task as done');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred');
        }
    };
</script>
@endpush

@endsection
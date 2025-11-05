@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-6">Archived Tasks</h1>
    
    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    <div class="space-y-4">
        @forelse($archivedTasks as $task)
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="font-medium">{{ $task->title }}</div>
                        <div class="text-sm text-gray-500">
                            @if($task->created_at)
                                Created on {{ $task->created_at->format('M d, Y') }}
                            @endif
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('tasks.restore', $task) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn btn-primary text-sm">Restore</button>
                        </form>
                        <form action="{{ route('tasks.force-delete', $task) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger text-sm" 
                                    onclick="return confirm('Are you sure you want to permanently delete this task?')">
                                Delete Permanently
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <p>No archived tasks found.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
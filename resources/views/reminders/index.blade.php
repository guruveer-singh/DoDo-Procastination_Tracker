@extends('layouts.app')

@section('content')
<div class="widget">
  <div class="section-title">🗓️ Your Reminders</div>

  {{-- Add new reminder --}}
  <form action="{{ route('reminders.store') }}" method="POST" class="reminder-form">
    @csrf
    <input type="text" name="title" placeholder="Add reminder title..." required>
    <input type="text" name="description" placeholder="Add description (optional)">
    <input type="datetime-local" name="reminder_time">
    <button type="submit">Add</button>
  </form>

  <ul class="reminder-list">
    @forelse ($reminders as $reminder)
      <li>
        <div>
          <strong>{{ $reminder->title }}</strong>
          @if($reminder->description)
            <p class="reminder-desc">{{ $reminder->description }}</p>
          @endif
        </div>
        <span class="reminder-time">
          {{ \Carbon\Carbon::parse($reminder->reminder_time)->format('D, h:i A') }}
        </span>
      </li>
    @empty
      <p>No reminders yet. Add one!</p>
    @endforelse
  </ul>
</div>
@endsection

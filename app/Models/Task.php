<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'title', 'notes', 'current_streak', 'best_streak', 'last_completed_date', 'is_archived', 'user_id'
    ];

    protected $casts = [
        'last_completed_date' => 'date',
        'is_archived' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                $task->user_id = \Illuminate\Support\Facades\Auth::id();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(TaskLog::class);
    }

    public function scopeCompletedToday($query)
    {
        return $query->whereDate('last_completed_date', now());
    }

    public function markDoneToday(): void
    {
        $today = now();
        
        // Prevent duplicate log for today using scope
        if ($this->logs()->forToday()->exists()) {
            return;
        }

        // Update streak logic
        if ($this->last_completed_date?->isYesterday()) {
            $this->current_streak++;
        } elseif (!$this->last_completed_date?->isToday()) {
            $this->current_streak = 1;
        }

        // Update best streak if current is better
        $this->best_streak = max($this->best_streak, $this->current_streak);
        $this->last_completed_date = $today;
        
        // Use transaction to ensure data consistency
        DB::transaction(function () use ($today) {
            $this->save();
            $this->logs()->create(['done_on' => $today]);
        });
    }

    /**
     * Mark the task as archived
     *
     * @return bool
     */
    public function markAsArchived(): bool
    {
        $this->is_archived = true;
        return $this->save();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Task;

class TaskLog extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'done_on'];
    protected $casts = ['done_on' => 'date'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function scopeForToday($query)
    {
        return $query->whereDate('done_on', now());
    }
}
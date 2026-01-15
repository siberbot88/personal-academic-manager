<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Task extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'title',
        'primary_course_id',
        'due_date',
        'status',
        'progress',
        'type_template_id',
        'task_type_template_id',
        'size',
        'progress_pct',
        'health_score',
        'health_status',
        'last_progress_at',
        'attention_flag',
        'priority_boost',
        'stagnation_days',
        'first_touched_at',
        'started_lead_days',
    ];

    protected $casts = [
        'due_date' => 'date',
        'first_touched_at' => 'datetime',
        'progress' => 'integer',
        'health_score' => 'integer',
        'last_progress_at' => 'datetime',
        'attention_flag' => 'boolean',
        'priority_boost' => 'boolean',
        'stagnation_days' => 'integer',
    ];

    /**
     * Idempotent method to mark task as started.
     */
    public function markAsStarted()
    {
        if ($this->first_touched_at) {
            return;
        }

        $this->first_touched_at = now();

        if ($this->due_date) {
            // false = absolute difference without rounding, using standard diffInDays for integer
            // we want integer days.
            $this->started_lead_days = $this->first_touched_at->diffInDays($this->due_date, false);
        }

        $this->save();
    }

    /**
     * Check if task was started before H-7 (7 days before due date).
     */
    public function getStartedBeforeH7Attribute(): bool
    {
        if (is_null($this->started_lead_days)) {
            return false;
        }
        return $this->started_lead_days >= 7;
    }

    public function primaryCourse()
    {
        return $this->belongsTo(Course::class, 'primary_course_id');
    }

    public function taskTypeTemplate()
    {
        return $this->belongsTo(TaskTypeTemplate::class, 'task_type_template_id');
    }

    public function taskPhases(): HasMany
    {
        return $this->hasMany(TaskPhase::class);
    }

    public function checklistItems(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(ChecklistItem::class, TaskPhase::class);
    }

    // booted() moved to TaskObserver

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}

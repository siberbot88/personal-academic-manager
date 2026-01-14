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
    ];

    protected $casts = [
        'due_date' => 'date',
        'progress' => 'integer',
        'health_score' => 'integer',
        'last_progress_at' => 'datetime',
        'attention_flag' => 'boolean',
        'priority_boost' => 'boolean',
        'stagnation_days' => 'integer',
    ];

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

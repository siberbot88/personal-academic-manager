<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TaskPhase extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'task_id',
        'phase_template_id',
        'title',
        'sort_order',
        'due_date',
        'start_date',
        'status', // todo, doing, done
        'progress_pct',
    ];

    protected $casts = [
        'due_date' => 'date',
        'start_date' => 'date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'due_date'])
            ->logOnlyDirty();
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function phaseTemplate(): BelongsTo
    {
        return $this->belongsTo(PhaseTemplate::class);
    }

    public function checklistItems(): HasMany
    {
        return $this->hasMany(ChecklistItem::class)->orderBy('sort_order');
    }
}

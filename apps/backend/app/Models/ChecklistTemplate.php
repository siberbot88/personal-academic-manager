<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ChecklistTemplate extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'task_type_template_id',
        'phase_template_id',
        'title',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function taskTypeTemplate()
    {
        return $this->belongsTo(TaskTypeTemplate::class);
    }

    public function phaseTemplate()
    {
        return $this->belongsTo(PhaseTemplate::class);
    }
}

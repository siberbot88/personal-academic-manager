<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class TaskTypeTemplate extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'big_threshold_days',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'big_threshold_days' => 'integer',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function phases()
    {
        return $this->belongsToMany(PhaseTemplate::class, 'task_type_template_phases')
            ->withPivot(['weight_percent', 'sort_order'])
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    public function checklistTemplates()
    {
        return $this->hasMany(ChecklistTemplate::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

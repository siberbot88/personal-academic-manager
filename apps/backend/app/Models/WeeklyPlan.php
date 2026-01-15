<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeeklyPlan extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'week_start',
        'focus_task_ids',
        'note',
    ];

    protected $casts = [
        'week_start' => 'date',
        'focus_task_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

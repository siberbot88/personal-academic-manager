<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Course;
use App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudySession extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'course_id',
        'task_id',
        'started_at',
        'ended_at',
        'duration_min',
        'mode',
        'note',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_min' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}

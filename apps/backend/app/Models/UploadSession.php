<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UploadSession extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function isValid(): bool
    {
        return $this->expires_at->isFuture() && is_null($this->used_at);
    }
}

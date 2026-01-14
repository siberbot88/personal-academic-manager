<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Tags\HasTags;

class InboxItem extends Model
{
    use HasFactory, HasTags;

    protected $guarded = [];

    protected $casts = [
        'captured_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    // Relations
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function promotedMaterial(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'promoted_to_material_id');
    }

    // Mutator: normalize URL
    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = trim($value);
    }

    // Accessor: Get domain from URL
    public function getDomainAttribute(): ?string
    {
        if (empty($this->url)) {
            return null;
        }

        $parsedUrl = parse_url($this->url);
        return $parsedUrl['host'] ?? null;
    }
}

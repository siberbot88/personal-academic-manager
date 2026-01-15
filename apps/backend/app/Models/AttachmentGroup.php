<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttachmentGroup extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class)->orderBy('version_number', 'desc');
    }

    public function currentAttachment()
    {
        return $this->hasOne(Attachment::class)->where('is_current', true);
    }
}

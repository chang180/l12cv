<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResumeVersion extends Model
{
    protected $fillable = [
        'resume_id',
        'event',
        'title',
        'snapshot',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }
}

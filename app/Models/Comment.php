<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'job_posting_id',
        'user_id',
        'body',
        'is_hidden',
    ];

    protected function casts(): array {
        return [
            'is_hidden' => 'boolean',
        ];
    }
    
    public function jobPosting(): BelongsTo {
        return $this->belongsTo(JobPosting::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}

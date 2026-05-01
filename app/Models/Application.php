<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    protected $fillable = ['job_posting_id', 'candidate_id',  'status', 'is_paid'];

    public function jobPosting(): BelongsTo {
        return $this->belongsTo(JobPosting::class);
    }

    public function candidate(): BelongsTo {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function payment(): HasOne {
        return $this->hasOne(Payment::class);
    }
}

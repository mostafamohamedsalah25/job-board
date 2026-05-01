<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employer extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'logo_path',
        'website'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function jobPostings(): HasMany {
        return $this->hasMany(JobPosting::class);
    }

    public function payments(): HasMany {
        return $this->hasMany(Payment::class);
    }
}

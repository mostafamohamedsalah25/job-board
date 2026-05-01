<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    protected $fillable = [
        'name'
    ];

    public function jobPostings(): BelongsToMany {
        return $this->belongsToMany(JobPosting::class);
    }

    public function profiles(): BelongsToMany {
        return $this->belongsToMany(Profile::class);
    }
}

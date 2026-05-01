<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'resume_path',
        'phone',
        'linkedin_id',
        'bio'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function skills(): BelongsToMany {
        return $this->belongsToMany(Skill::class);
    }
}

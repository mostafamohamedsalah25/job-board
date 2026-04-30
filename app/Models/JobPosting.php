<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends Model
{
    protected $fillable = [
        'employer_id',
        'category_id',
        'title',
        'description',
        'salary_range',
        'location',
        'deadline',
        'is_active',
        'type',
        'status'
    ];

    protected function casts(): array {
        return [
            'deadline' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function employer(): BelongsTo {
        return $this->belongsTo(Employer::class);
    }

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function skills(): BelongsToMany {
        return $this->belongsToMany(Skill::class);
    }

    public function applications(): HasMany {
        return $this->hasMany(Application::class);
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }
}

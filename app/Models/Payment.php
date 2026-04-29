<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'employer_id',
        'application_id',
        'amount',
        'transaction_id',
        'status',
        'paid_at'
    ];

    protected function casts(): array {
        return [
            'paid_at' => 'datetime',
        ];
    }

    public function employer(): BelongsTo {
        return $this->belongsTo(Employer::class);
    }

    public function application(): BelongsTo {
        return $this->belongsTo(Application::class);
    }
}

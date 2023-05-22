<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $fillable = [
        'phone_number',
        'height',
        'weight',
        'other_information',
    ];

    /**
     * @return BelongsTo<User, Patient>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace SurazDott\TwoStep\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwoStepVerification extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verificatin_code_expires_at' => 'datetime',
    ];

    /**
     * The attributes that should be protected.
     *
     * @var array
     */
    protected $dates = [
        'verificatin_code_expires_at',
    ];

    /**
     * Two step verification belongsTo user
     *
     * @return void
     */
    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JwtToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'unique_id',
        'token_title',
        'restrictions',
        'permissions',
        'expires_at',
        'last_used_at',
        'refreshed_at',
    ];

    protected $casts = [
        'restrictions' => 'array',
        'permissions' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Makes the token invalid when the user logs out.
     *
     * @return bool
     */
    public function invalidate(): bool
    {
        // Set the expiration time to the current time to invalidate the token
        $this->expires_at = now();
        return $this->save();
    }

    /**
     * Checks the validity status of the token.
     *
     * @return bool
     */
    public function isValid(): bool
    {
        // Check if the token has not expired (expires_at is in the future)
        return now()->lt($this->expires_at);
    }
}

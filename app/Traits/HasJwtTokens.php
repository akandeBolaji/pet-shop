<?php

namespace App\Traits;

use App\Models\JwtToken;
use App\Libraries\JWTLibraryClient;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasJwtTokens
{
    /**
     * The current JwtToken.
     */
    protected JwtToken $jwt_token;

    /**
     * Get the access tokens that belong to model.
     *
     * @return HasMany<JwtToken>
     */
    public function tokens(): HasMany
    {
        return $this->hasMany(JwtToken::class);
    }

    /**
     * Create a new jwt token for the user.
     */
    public function createToken(): string
    {
        $jwt_library_client = app(JWTLibraryClient::class);

        $issued_token = $jwt_library_client->issueToken($this->uuid);

        $this->tokens()->create([
            'token_title' => 'JWT',
            'unique_id' => $issued_token->unique_id,
            'expires_at' => $issued_token->expires_at,
            'is_valid' => true,
        ]);

        return $issued_token->token;
    }

    public function setCurrentJwtToken(JwtToken $jwt_token): void
    {
        $this->jwt_token = $jwt_token;

        $this->jwt_token->saveLastUsedTime();
    }

    /**
     * Invalidate the current token.
     */
    public function invalidateToken(): bool
    {
        return $this->jwt_token->invalidate();
    }
}

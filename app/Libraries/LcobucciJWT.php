<?php

namespace App\Libraries;

use App\DTOs\IssuedToken;
use App\Models\JwtToken;
use Exception;
use Illuminate\Support\Str;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;

class LcobucciJWT extends JWTLibraryClient
{
    private Configuration $config;

    /**
     * Issue the jwt and return the string.
     *
     * @param string $user_identifier
     * @return IssuedToken
     */
    public function issueToken(string $user_identifier): IssuedToken
    {
        $now = new \DateTimeImmutable();
        $unique_id = Str::random(20);
        $expires_at = $now->modify('+ '.$this->expires_in.' seconds');

        assert(is_string($this->issuer) && $this->issuer !== '', 'Issuer should be a non-empty string.');
        assert(is_string($unique_id) && $unique_id !== '', 'Unique ID should be a non-empty string.');

        $token = $this->config->builder()
            ->issuedBy($this->issuer) // Configures the issuer (iss claim)
            ->identifiedBy($unique_id) // Configures the id (jti claim), replicating as a header item
            ->issuedAt($now) // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($now) // Configures the time that the token can be used (nbf claim)
            ->expiresAt($expires_at) // Configures the expiration time of the token (exp claim)
            ->withClaim('uid', $user_identifier) // Configures a new claim, called "uid"
            ->getToken($this->config->signer(), $this->config->signingKey()); // Builds a new token

        return new IssuedToken($token->toString(), $unique_id, $expires_at);
    }

    /**
     * Get the stored jwtToken if token is valid.
     *
     * @param string $token
     * @return JwtToken|null
     */
    public function getJwtToken(string $token): ?JwtToken
    {
        try {
            //parse the token
            assert(is_string($token) && $token !== '', 'Token should be a non-empty string.');
            /** @var UnencryptedToken $unencrypted_token */
            $unencrypted_token = $this->config->parser()->parse($token);

            //validate token against the constraints set
            $constraints = $this->config->validationConstraints();
            if ($this->config->validator()->validate($unencrypted_token, ...$constraints)) {
                //get the jwt token that was stored
                $unique_id = $unencrypted_token->claims()->get('jti');
                $jwtToken = $this->getStoredJwtToken($unique_id);

                if ($jwtToken !== null && $jwtToken->isValid()) {
                    return $jwtToken;
                }
            }

            return null;
        } catch (Exception) {
            return null;
        }
    }

    protected function configure(): void
    {
        $signer = new Signer\Hmac\Sha256();
        assert(is_string($this->private_key) && $this->private_key !== '', 'Private key should be a non-empty string.');
        assert(is_string($this->public_key) && $this->public_key !== '', 'Public key should be a non-empty string.');
        $private_key = InMemory::plainText($this->private_key);
        $public_key = InMemory::plainText($this->public_key);

        $this->config = Configuration::forAsymmetricSigner($signer, $private_key, $public_key);

        $clock = SystemClock::fromSystemTimezone();

        assert(is_string($this->issuer) && $this->issuer !== '', 'Issuer should be a non-empty string.');
        $this->config->setValidationConstraints(
            new IssuedBy($this->issuer),
            new SignedWith($signer, $private_key),
            new StrictValidAt($clock),
            new LooseValidAt($clock)
        );
    }
}

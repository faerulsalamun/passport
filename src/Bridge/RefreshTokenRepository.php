<?php

namespace Laravel\Passport\Bridge;

use Illuminate\Database\Connection;
use Illuminate\Contracts\Events\Dispatcher;
use Laravel\Passport\Events\RefreshTokenCreated;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * The access token repository instance.
     *
     * @var \Laravel\Passport\Bridge\AccessTokenRepository
     */
    protected $tokens;

    /**
     * The database connection.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $database;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Create a new repository instance.
     *
     * @param  \Laravel\Passport\Bridge\AccessTokenRepository  $tokens
     * @param  \Illuminate\Database\Connection  $database
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function __construct(AccessTokenRepository $tokens,
                                Connection $database,
                                Dispatcher $events)
    {
        $this->events = $events;
        $this->tokens = $tokens;
        $this->database = $database;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken()
    {
        return new RefreshToken;
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity)
    {
        $this->database->table('OAUTH_REFRESH_TOKENS')->insert([
            'ID' => $id = $refreshTokenEntity->getIdentifier(),
            'ACCESS_TOKEN_ID' => $accessTokenId = $refreshTokenEntity->getAccessToken()->getIdentifier(),
            'REVOKED' => false,
            'EXPIRES_AT' => $refreshTokenEntity->getExpiryDateTime(),
        ]);

        $this->events->fire(new RefreshTokenCreated($id, $accessTokenId));
    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken($tokenId)
    {
        $this->database->table('OAUTH_REFRESH_TOKENS')
                    ->where('ID', $tokenId)->update(['REVOKED' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        $refreshToken = $this->database->table('OAUTH_REFRESH_TOKENS')
                    ->where('ID', $tokenId)->first();

        if ($refreshToken === null || $refreshToken->REVOKED) {
            return true;
        }

        return $this->tokens->isAccessTokenRevoked(
            $refreshToken->ACCESS_TOKEN_ID
        );
    }
}

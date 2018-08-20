<?php

namespace Laravel\Passport\Bridge;

use Illuminate\Database\Connection;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    use FormatsScopesForStorage;

    /**
     * The database connection.
     *
     * @var \Illuminate\Database\Connection
     */
    protected $database;

    /**
     * Create a new repository instance.
     *
     * @param  \Illuminate\Database\Connection  $database
     * @return void
     */
    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    /**
     * {@inheritdoc}
     */
    public function getNewAuthCode()
    {
        return new AuthCode;
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
        $this->database->table('OAUTH_AUTH_CODES')->insert([
            'ID' => $authCodeEntity->getIdentifier(),
            'USER_ID' => $authCodeEntity->getUserIdentifier(),
            'CLIENT_ID' => $authCodeEntity->getClient()->getIdentifier(),
            'SCOPES' => $this->formatScopesForStorage($authCodeEntity->getScopes()),
            'REVOKED' => false,
            'EXPIRES_AT' => $authCodeEntity->getExpiryDateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAuthCode($codeId)
    {
        $this->database->table('OAUTH_AUTH_CODES')
                    ->where('ID', $codeId)->update(['REVOKED' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function isAuthCodeRevoked($codeId)
    {
        return $this->database->table('OAUTH_AUTH_CODES')
                    ->where('ID', $codeId)->where('REVOKED', 1)->exists();
    }
}

<?php

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class BridgeAccessTokenRepositoryTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function test_access_tokens_can_be_persisted()
    {
        $expiration = Carbon::now();

        $tokenRepository = Mockery::mock('Laravel\Passport\TokenRepository');

        $events = Mockery::mock('Illuminate\Contracts\Events\Dispatcher');

        $tokenRepository->shouldReceive('create')->once()->andReturnUsing(function ($array) use ($expiration) {
            $this->assertEquals(1, $array['ID']);
            $this->assertEquals(2, $array['USER_ID']);
            $this->assertEquals('client-ID', $array['CLIENT_ID']);
            $this->assertEquals(['SCOPES'], $array['SCOPES']);
            $this->assertEquals(false, $array['REVOKED']);
            $this->assertInstanceOf('DateTime', $array['CREATED_AT']);
            $this->assertInstanceOf('DateTime', $array['UPDATED_AT']);
            $this->assertEquals($expiration, $array['EXPIRES_AT']);
        });

        $events->shouldReceive('dispatch')->once();

        $accessToken = new Laravel\Passport\Bridge\AccessToken(2, [new Laravel\Passport\Bridge\Scope('SCOPES')]);
        $accessToken->setIdentifier(1);
        $accessToken->setExpiryDateTime($expiration);
        $accessToken->setClient(new Laravel\Passport\Bridge\Client('client-ID', 'NAME', 'REDIRECT'));

        $repository = new Laravel\Passport\Bridge\AccessTokenRepository($tokenRepository, $events);

        $repository->persistNewAccessToken($accessToken);
    }
}

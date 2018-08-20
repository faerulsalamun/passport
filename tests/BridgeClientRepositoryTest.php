<?php

use PHPUnit\Framework\TestCase;
use Laravel\Passport\Bridge\ClientRepository;

class BridgeClientRepositoryTest extends TestCase
{
    public function setUp()
    {
        $clientModelRepository = Mockery::mock(Laravel\Passport\ClientRepository::class);
        $clientModelRepository->shouldReceive('findActive')->with(1)->andReturn(new BridgeClientRepositoryTestClientStub);

        $this->clientModelRepository = $clientModelRepository;
        $this->repository = new Laravel\Passport\Bridge\ClientRepository($clientModelRepository);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function test_can_get_client_for_auth_code_grant()
    {
        $client = $this->repository->getClientEntity(1, 'authorization_code', 'SECRET', true);

        $this->assertInstanceOf('Laravel\Passport\Bridge\Client', $client);
        $this->assertNull($this->repository->getClientEntity(1, 'authorization_code', 'wrong-SECRET', true));
        $this->assertNull($this->repository->getClientEntity(1, 'client_credentials', 'wrong-SECRET', true));
    }

    public function test_can_get_client_for_client_credentials_grant()
    {
        $client = $this->clientModelRepository->findActive(1);
        $client->PERSONAL_ACCESS_CLIENT = true;

        $this->assertInstanceOf('Laravel\Passport\Bridge\Client', $this->repository->getClientEntity(1, 'client_credentials', 'SECRET', true));
        $this->assertNull($this->repository->getClientEntity(1, 'authorization_code', 'SECRET', true));
    }

    public function test_password_grant_is_permitted()
    {
        $client = $this->clientModelRepository->findActive(1);
        $client->PASSWORD_CLIENT = true;

        $this->assertInstanceOf('Laravel\Passport\Bridge\Client', $this->repository->getClientEntity(1, 'password', 'SECRET'));
    }

    public function test_password_grant_is_prevented()
    {
        $this->assertNull($this->repository->getClientEntity(1, 'password', 'SECRET'));
    }

    public function test_authorization_code_grant_is_permitted()
    {
        $this->assertInstanceOf('Laravel\Passport\Bridge\Client', $this->repository->getClientEntity(1, 'authorization_code', 'SECRET'));
    }

    public function test_authorization_code_grant_is_prevented()
    {
        $client = $this->clientModelRepository->findActive(1);
        $client->PASSWORD_CLIENT = true;

        $this->assertNull($this->repository->getClientEntity(1, 'authorization_code', 'SECRET'));
    }

    public function test_personal_access_grant_is_permitted()
    {
        $client = $this->clientModelRepository->findActive(1);
        $client->PERSONAL_ACCESS_CLIENT = true;

        $this->assertInstanceOf('Laravel\Passport\Bridge\Client', $this->repository->getClientEntity(1, 'personal_access', 'SECRET'));
    }

    public function test_personal_access_grant_is_prevented()
    {
        $this->assertNull($this->repository->getClientEntity(1, 'personal_access', 'SECRET'));
    }

    public function test_client_credentials_grant_is_permitted()
    {
        $this->assertInstanceOf('Laravel\Passport\Bridge\Client', $this->repository->getClientEntity(1, 'client_credentials', 'SECRET'));
    }

    public function test_client_credentials_grant_is_prevented()
    {
        $client = $this->clientModelRepository->findActive(1);
        $client->SECRET = null;

        $this->assertNull($this->repository->getClientEntity(1, 'client_credentials', 'SECRET'));
    }

    public function test_grant_types_allows_request()
    {
        $client = $this->clientModelRepository->findActive(1);
        $client->grant_types = ['client_credentials'];

        $this->assertInstanceOf('Laravel\Passport\Bridge\Client', $this->repository->getClientEntity(1, 'client_credentials', 'SECRET'));
    }

    public function test_grant_types_disallows_request()
    {
        $client = $this->clientModelRepository->findActive(1);
        $client->grant_types = ['client_credentials'];

        $this->assertNull($this->repository->getClientEntity(1, 'authorization_code', 'SECRET'));
    }
}

class BridgeClientRepositoryTestClientStub
{
    public $name = 'Client';
    public $redirect = 'http://localhost';
    public $secret = 'SECRET';
    public $personal_access_client = false;
    public $password_client = false;
    public $grant_types;

    public function firstParty()
    {
        return $this->PERSONAL_ACCESS_CLIENT || $this->PASSWORD_CLIENT;
    }
}

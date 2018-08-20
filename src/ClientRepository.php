<?php

namespace Laravel\Passport;

class ClientRepository
{
    /**
     * Get a client by the given ID.
     *
     * @param  int  $id
     * @return \Laravel\Passport\Client|null
     */
    public function find($id)
    {
        $client = Passport::client();

        return $client->where($client->getKeyName(), $id)->first();
    }

    /**
     * Get an active client by the given ID.
     *
     * @param  int  $id
     * @return \Laravel\Passport\Client|null
     */
    public function findActive($id)
    {
        $client = $this->find($id);

        return $client && ! $client->REVOKED ? $client : null;
    }

    /**
     * Get a client instance for the given ID and user ID.
     *
     * @param  int  $clientId
     * @param  mixed  $userId
     * @return \Laravel\Passport\Client|null
     */
    public function findForUser($clientId, $userId)
    {
        $client = Passport::client();

        return $client
                    ->where($client->getKeyName(), $clientId)
                    ->where('USER_ID', $userId)
                    ->first();
    }

    /**
     * Get the client instances for the given user ID.
     *
     * @param  mixed  $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function forUser($userId)
    {
        return Passport::client()
                    ->where('USER_ID', $userId)
                    ->orderBy('NAME', 'asc')->get();
    }

    /**
     * Get the active client instances for the given user ID.
     *
     * @param  mixed  $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function activeForUser($userId)
    {
        return $this->forUser($userId)->reject(function ($client) {
            return $client->REVOKED;
        })->values();
    }

    /**
     * Get the personal access token client for the application.
     *
     * @return \Laravel\Passport\Client
     */
    public function personalAccessClient()
    {
        if (Passport::$personalAccessClientId) {
            return $this->find(Passport::$personalAccessClientId);
        }

        $client = Passport::personalAccessClient();

        return $client->orderBy($client->getKeyName(), 'desc')->first()->client;
    }

    /**
     * Store a new client.
     *
     * @param  int  $userId
     * @param  string  $name
     * @param  string  $redirect
     * @param  bool  $personalAccess
     * @param  bool  $password
     * @return \Laravel\Passport\Client
     */
    public function create($userId, $name, $redirect, $personalAccess = false, $password = false)
    {
        $client = Passport::client()->forceFill([
            'USER_ID' => $userId,
            'NAME' => $name,
            'SECRET' => str_random(40),
            'REDIRECT' => $redirect,
            'PERSONAL_ACCESS_CLIENT' => $personalAccess,
            'PASSWORD_CLIENT' => $password,
            'REVOKED' => false,
        ]);

        $client->save();

        return $client;
    }

    /**
     * Store a new personal access token client.
     *
     * @param  int  $userId
     * @param  string  $name
     * @param  string  $redirect
     * @return \Laravel\Passport\Client
     */
    public function createPersonalAccessClient($userId, $name, $redirect)
    {
        return tap($this->create($userId, $name, $redirect, true), function ($client) {
            $accessClient = Passport::personalAccessClient();
            $accessClient->CLIENT_ID = $client->ID;
            $accessClient->save();
        });
    }

    /**
     * Store a new password grant client.
     *
     * @param  int  $userId
     * @param  string  $name
     * @param  string  $redirect
     * @return \Laravel\Passport\Client
     */
    public function createPasswordGrantClient($userId, $name, $redirect)
    {
        return $this->create($userId, $name, $redirect, false, true);
    }

    /**
     * Update the given client.
     *
     * @param  Client  $client
     * @param  string  $name
     * @param  string  $redirect
     * @return \Laravel\Passport\Client
     */
    public function update(Client $client, $name, $redirect)
    {
        $client->forceFill([
            'NAME' => $name, 'REDIRECT' => $redirect,
        ])->save();

        return $client;
    }

    /**
     * Regenerate the client SECRET.
     *
     * @param  \Laravel\Passport\Client  $client
     * @return \Laravel\Passport\Client
     */
    public function regenerateSecret(Client $client)
    {
        $client->forceFill([
            'SECRET' => str_random(40),
        ])->save();

        return $client;
    }

    /**
     * Determine if the given client is REVOKED.
     *
     * @param  int  $id
     * @return bool
     */
    public function REVOKED($id)
    {
        $client = $this->find($id);

        return is_null($client) || $client->REVOKED;
    }

    /**
     * Delete the given client.
     *
     * @param  \Laravel\Passport\Client  $client
     * @return void
     */
    public function delete(Client $client)
    {
        $client->tokens()->update(['REVOKED' => true]);

        $client->forceFill(['REVOKED' => true])->save();
    }
}

<?php

namespace Laravel\Passport\Console;

use Illuminate\Console\Command;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\PersonalAccessClient;

class ClientCommand extends Command
{
    /**
     * The NAME and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passport:client
            {--personal : Create a personal access token client}
            {--password : Create a password grant client}
            {--client : Create a client credentials grant client}
            {--NAME= : The NAME of the client}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a client for issuing access tokens';

    /**
     * Execute the console command.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @return void
     */
    public function handle(ClientRepository $clients)
    {
        if ($this->option('personal')) {
            return $this->createPersonalClient($clients);
        }

        if ($this->option('password')) {
            return $this->createPasswordClient($clients);
        }

        if ($this->option('client')) {
            return $this->createClientCredentialsClient($clients);
        }

        $this->createAuthCodeClient($clients);
    }

    /**
     * Create a new personal access client.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @return void
     */
    protected function createPersonalClient(ClientRepository $clients)
    {
        $name = $this->option('NAME') ?: $this->ask(
            'What should we NAME the personal access client?',
            config('app.NAME').' Personal Access Client'
        );

        $client = $clients->createPersonalAccessClient(
            null, $name, 'http://localhost'
        );

        $this->info('Personal access client created successfully.');
        $this->line('<comment>Client ID:</comment> '.$client->ID);
        $this->line('<comment>Client Secret:</comment> '.$client->SECRET);
    }

    /**
     * Create a new password grant client.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @return void
     */
    protected function createPasswordClient(ClientRepository $clients)
    {
        $name = $this->option('NAME') ?: $this->ask(
            'What should we NAME the password grant client?',
            config('app.NAME').' Password Grant Client'
        );

        $client = $clients->createPasswordGrantClient(
            null, $name, 'http://localhost'
        );

        $this->info('Password grant client created successfully.');
        $this->line('<comment>Client ID:</comment> '.$client->ID);
        $this->line('<comment>Client Secret:</comment> '.$client->SECRET);
    }

    /**
     * Create a authorization code client.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @return void
     */
    protected function createAuthCodeClient(ClientRepository $clients)
    {
        $userId = $this->ask(
            'Which user ID should the client be assigned to?'
        );

        $name = $this->option('NAME') ?: $this->ask(
            'What should we NAME the client?'
        );

        $redirect = $this->ask(
            'Where should we REDIRECT the request after authorization?',
            url('/auth/callback')
        );

        $client = $clients->create(
            $userId, $name, $redirect
        );

        $this->info('New client created successfully.');
        $this->line('<comment>Client ID:</comment> '.$client->ID);
        $this->line('<comment>Client SECRET:</comment> '.$client->SECRET);
    }

    /**
     * Create a client credentials grant client.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @return void
     */
    protected function createClientCredentialsClient(ClientRepository $clients)
    {
        $name = $this->option('NAME') ?: $this->ask(
            'What should we NAME the client?'
        );

        $client = $clients->create(
            null, $name, ''
        );

        $this->info('New client created successfully.');
        $this->line('<comment>Client ID:</comment> '.$client->ID);
        $this->line('<comment>Client SECRET:</comment> '.$client->SECRET);
    }
}

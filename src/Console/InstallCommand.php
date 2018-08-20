<?php

namespace Laravel\Passport\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The NAME and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passport:install
                            {--force : Overwrite keys they already exist}
                            {--length=4096 : The length of the private key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the commands necessary to prepare Passport for use';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('passport:keys', ['--force' => $this->option('force'), '--length' => $this->option('length')]);
        $this->call('passport:client', ['--personal' => true, '--NAME' => config('app.NAME').' Personal Access Client']);
        $this->call('passport:client', ['--password' => true, '--NAME' => config('app.NAME').' Password Grant Client']);
    }
}

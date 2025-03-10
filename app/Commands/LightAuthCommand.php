<?php

namespace App\Commands;

use Illuminate\Console\Command;

class LightAuthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'light:auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Implement the authentication system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $option = $this->confirm('Do you want to generate password-less authentication system?', false);

        return match ($option) {
            true => $this->generatePasswordLessAuth(),
            false => $this->generatePasswordAuth(),
        };
    }

    private function generatePasswordAuth()
    {
        $this->info('Generating password-based authentication system...');

        /**
         * Generate the requests.
         */

        /**
         * Generate the authentication controllers.
         */
    }

    private function generatePasswordLessAuth()
    {
        $this->info('Generating password-less authentication system...');

        // $this->call('make:controller', ['name' => 'Auth\LoginController']);
        // $this->call('make:controller', ['name' => 'Auth\RegisterController']);
        // $this->call('make:controller', ['name' => 'Auth\VerificationController']);
        // $this->call('make:controller', ['name' => 'Auth\LogoutController']);
        // $this->call('make:controller', ['name' => 'Auth\MeController']);
    }
}

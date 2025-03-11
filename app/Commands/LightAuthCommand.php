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
        $basePathRequest = "app/Http/Requests/Auth";
        $requestNames = [
            'ForgotRequest',
            'LoginRequest',
            'RegisterRequest',
            'ResetRequest',
            'VerifyRequest',
        ];

        foreach ($requestNames as $initial_name) {
            if (file_exists("$basePathRequest/$initial_name.php")) {
                $this->error('Requests file already exists..!');
                return 0;
            }
        }

        foreach ($requestNames as $initial_name) {
            $stub = file_get_contents(resource_path("stubs/auth/with-password/requests/$initial_name.stub"));
            if (!file_exists($basePathRequest)) {
                mkdir($basePathRequest, 0755, true);
            }
            file_put_contents("$basePathRequest/$initial_name.php", $stub);
        }

        /**
         * Generate the authentication controllers.
         */
        $basePathController = "app/Http/Controllers/Auth";
        $controllerNames = [
            'AuthenticateController',
            'ForgotPasswordController',
        ];

        foreach ($controllerNames as $initial_name) {
            if (file_exists("$basePathController/$initial_name.php")) {
                $this->error('Requests file already exists..!');
                return 0;
            }
        }

        foreach ($controllerNames as $initial_name) {
            $stub = file_get_contents(resource_path("stubs/auth/with-password/controllers/$initial_name.stub"));
            if (!file_exists($basePathController)) {
                mkdir($basePathController, 0755, true);
            }
            file_put_contents("$basePathController/$initial_name.php", $stub);
        }

        /**
         * Generate the routes.
         */
        $this->info('Generating routes for authentication system...');

        $basePathRoutes = "routes/auth.php";
        if (file_exists($basePathRoutes)) {
            $this->error('Routes file already exists..!');
            return;
        }

        $routeStub = file_get_contents(resource_path("stubs/auth/with-password/route/auth.stub"));
        file_put_contents($basePathRoutes, $routeStub);

        /**
         * Mailer
         */
        $this->info('Generating mailer for authentication system...');
        $basePathMailer = "app/Mail/Auth";
        $mailerNames = [
            'ForgotPasswordMail',
            'VerifyMail',
        ];

        foreach ($mailerNames as $initial_name) {
            if (file_exists("$basePathMailer/$initial_name.php")) {
                $this->error('Mailer is exist..!');
                return 0;
            }
        }

        foreach ($mailerNames as $initial_name) {
            $stub = file_get_contents(resource_path("stubs/auth/with-password/mails/$initial_name.stub"));
            if (!file_exists($basePathMailer)) {
                mkdir($basePathMailer, 0755, true);
            }

            file_put_contents("$basePathMailer/$initial_name.php", $stub);
        }

        /**
         * Generate for verification
         */
        $this->info('TODO: Generating verification system...');

        /**
         * Update the files
         * bootstrap/app.php
         * app/Models/User.php
         */
        $this->info('TODO: Updating the files...');
        // $appFile = file_get_contents(base_path('bootstrap/app.php'));

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

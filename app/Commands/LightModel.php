<?php

namespace App\Commands;

use Illuminate\Console\Command;

class LightModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:light-model {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make the Light Model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $base_path = 'app/Models';

        if (file_exists("$base_path/$name.php")) {
            $this->error('Model is exist..!');
            return 0;
        }

        $stub = file_get_contents(resource_path('stubs/light-model.stub'));

        $stub = str_replace(
            ['{{ name }}'],
            [$name],
            $stub
        );

        file_put_contents("$base_path/$name.php", $stub);

        $this->info("Successfully Create Light Model $name...");
    }
}

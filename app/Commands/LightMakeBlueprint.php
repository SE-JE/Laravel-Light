<?php

namespace App\Commands;

use Illuminate\Console\Command;

class LightMakeBlueprint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'light:make-blueprint {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate blueprint';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $base_path = 'app/BLueprints';

        if (file_exists("$base_path/$name.php")) {
            $this->error('Blueprint is exist..!');
            return 0;
        }

        $stub = file_get_contents(resource_path('stubs/light-blueprint.stub'));

        $stub = str_replace(
            ['{{ name }}'],
            [$name],
            $stub
        );

        file_put_contents("$base_path/$name.php", $stub);

        $this->info("Successfully Create Blueprint  $name...");
    }

    
}

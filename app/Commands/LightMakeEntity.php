<?php

namespace App\Commands;

use Illuminate\Console\Command;

class LightMakeEntity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'light:make-entity {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate entity';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $base_path = 'app/Entities';

        if (file_exists("$base_path/$name.php")) {
            $this->error('Entity is exist..!');
            return 0;
        }

        $stub = file_get_contents(resource_path('stubs/light-entity.stub'));

        $stub = str_replace(
            ['{{ name }}'],
            [$name],
            $stub
        );

        file_put_contents("$base_path/$name.php", $stub);

        $this->info("Successfully Create Light Entity $name...");
    }

    
}

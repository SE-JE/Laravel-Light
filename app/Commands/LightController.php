<?php

namespace App\Commands;

use Illuminate\Console\Command;

class LightController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:light-controller {name} {--model=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make the Light Controller';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $initial_name = $this->argument('name');
        $model = $this->option('model') ? $this->option('model') : "// model";
        $base_path = 'app/Http/Controllers';

        if ($initial_name === '' || is_null($initial_name) || empty($initial_name)) {
            $this->error('Controller Name Invalid..!');
            return 0;
        }

        $names = explode('/', $initial_name);
        $name = $names[count($names)-1];
        array_pop($names);
        $folder = implode('/', $names);

        if (file_exists("$base_path/$initial_name.php")) {
            $this->error('Controller is exist..!');
            return 0;
        }

        if (!file_exists("$base_path/$folder")) {
            mkdir("$base_path/$folder", 0775, true);
            $this->info("Create folder $base_path/$folder...");
        }

        $stub = file_get_contents(resource_path('stubs/light-controller.stub'));

        $stub = str_replace(
            ['{{ namespace }}', '{{ name }}', '{{ model }}'],
            [$folder ? "\\" . $folder : "", $name, $model],
            $stub
        );

        file_put_contents("$base_path/$initial_name.php", $stub);

        $this->info("Successfully Create Light Controller $base_path/$initial_name...");
    }
}

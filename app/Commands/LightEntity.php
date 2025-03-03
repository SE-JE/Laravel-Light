<?php

namespace App\Commands;

use App\Entities\BaseEntity;
use App\Entities\StarterEntity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class LightEntity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'light:entity {entity?}';

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
        $entity = $this->argument('entity');

        if ($entity) {
            $runnerClass = "\\App\\Entities\\{$entity}";

            if (class_exists($runnerClass)) {
                (new $runnerClass)->run();
                $this->info("Successfully Generate $runnerClass Entity.");
            } else {
                $this->error("$runnerClass Entity Not Found!");
            }
        } else {

            $runChoice = $this->choice('Choose what you want to run?', ['Run Starter Entity', 'Run Registered Entity']);

            if ($runChoice) {
                (new StarterEntity)->run();
                $this->info("Successfully Generate Starter Entities");
            } else {
                (new BaseEntity)->run();
                $this->info("Successfully Generate Registered Entities");
            }
        }
    }

    
}

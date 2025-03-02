<?php

namespace App\Commands;

use App\Entities\BaseEntity;
use Illuminate\Console\Command;

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
            (new BaseEntity)->run();
            $this->info("Successfully Generate All Entities");
        }
    }

    
}

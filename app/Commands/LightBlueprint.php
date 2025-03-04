<?php

namespace App\Commands;

use App\Blueprints\BaseBLueprint;
use App\Blueprints\StarterBLueprint;
use Illuminate\Console\Command;

class LightBlueprint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'light:blueprint {blueprint?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run blueprint generation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $blueprint = $this->argument('blueprint');

        if ($blueprint) {
            $runnerClass = "\\App\\Blueprints\\{$blueprint}";

            if (class_exists($runnerClass)) {
                (new $runnerClass)->run();
                $this->info("Successfully Generate $runnerClass Blueprint.");
            } else {
                $this->error("$runnerClass Blueprint Not Found!");
            }
        } else {

            $runChoice = $this->choice('Choose what you want to run?', ['Run Starter BLueprint', 'Run Registered BLueprint']);

            if ($runChoice) {
                (new StarterBLueprint)->run();
                $this->info("Successfully Generate Starter BLueprints");
            } else {
                (new BaseBLueprint)->run();
                $this->info("Successfully Generate Registered Blueprints");
            }
        }
    }

    
}

<?php

namespace App\Blueprints;

use App\Helpers\LightGenerationHelper;

class BaseBlueprint
{
    use LightGenerationHelper;
    /**
     * generation the application's entity.
     */
    public function run(): void
    {
        $this->call([
            // call entity resource
        ]);
    }
}

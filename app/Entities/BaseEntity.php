<?php

namespace App\Entities;

use App\Helpers\LightGenerationHelper;

class BaseEntity
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

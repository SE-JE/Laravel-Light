<?php

namespace App\Helpers;

use Illuminate\Support\Str;

function renderArray(array $array) {
    if(!count($array)) return;
    $return = "\n";
    foreach ($array as $item) {
        $return .= "        \"$item\",\n";
    }
    return $return .= "    ";
}

trait LightGenerationHelper
{
    // =========================>
    // ## Call Entity Resources
    // =========================>
    public function call($entities)
    {
        foreach ($entities as $entity) {
            (new $entity)->run();
        }
    }

    // =========================>
    // ## Entity Generation
    // =========================>
    public function entity(array $structs)
    {
        foreach ($structs as $struct) {
            $this->modelGeneration(
                $struct['model'], 
                isset($struct['schema']) ? $struct['schema'] : [],
                isset($struct['relations']) ? $struct['relations'] : [],
            );
            $this->migrationGeneration(
                $struct['model'],
                isset($struct['schema']) ? $struct['schema'] : ''
            );
            
            if(!isset($struct['controllers']) || (isset($struct['controllers']) && $struct['controllers'] !== false)) {
                if(isset($struct['controllers']) && count($struct['controllers'])) {
                    foreach ($struct['controllers'] as $route => $controller) {
                        $this->controllerGeneration(
                            $struct['model'], 
                            isset($struct['schema']) ? $struct['schema'] : [],
                            isset($struct['relations']) ? $struct['relations'] : [],
                            $controller,
                            $route,
                        );
                    }
                } else {
                    $this->controllerGeneration(
                        $struct['model'],
                        isset($struct['schema']) ? $struct['schema'] : [],
                        isset($struct['relations']) ? $struct['relations'] : [],
                    );
                }
            }
        }
    }

    // =========================>
    // ## Light Model Generation
    // =========================>
    private function modelGeneration(string $model, array $schema = [], array $relations = [])
    {
        $name = $model;
        $base_path = 'app/Models';
        
        if (file_exists("$base_path/$name.php")) {
            unlink("$base_path/$name.php");
        }
        
        $fillable = [];
        $searchable = [];
        $selectable = ['id'];
        $hidden = [];
        
        foreach ($schema as $column => $definition) {
            if (str_contains($definition, 'fillable')) {
                $fillable[] = $column;
            }
            if (str_contains($definition, 'searchable')) {
                $searchable[] = $column;
            }
            if (str_contains($definition, 'selectable')) {
                $selectable[] = $column;
            }
            if (str_contains($definition, 'hidden')) {
                $hidden[] = $column;
            }
        }
        
        $modelRelations = [];

        foreach ($relations as $relationName => $relationType) {
            $relation = explode(',', $relationType);
            $fk =  isset($relation[1]) ? ", '$relation[1]'" : "";
            $ok =  isset($relation[2]) ? ", '$relation[2]'" : ""; 

            if (str_starts_with($relationType, '[]')) {
                $relatedModel = substr($relation[0], 2);
                $method = "    public function $relationName() {\n";
                $method .= "        return \$this->hasMany($relatedModel::class$fk$ok);\n";
                $method .= "    }\n";
            } else {
                $relatedModel = $relation[0];
                $method = "    public function $relationName() {\n";
                $method .= "        return \$this->belongsTo($relatedModel::class$fk$ok);\n";
                $method .= "    }\n";
            }
        
            $modelRelations[] = $method;
        }

        $modelRelationsCode = implode("\n", $modelRelations);

        $stub = file_get_contents(resource_path('stubs/light-model.stub'));

        $stub = str_replace(
            ['{{ name }}', '{{ fillable }}', '{{ selectable }}', '{{ searchable }}', '{{ hidden }}', '{{ relations }}'],
            [$name, renderArray($fillable), renderArray($selectable), renderArray($searchable), renderArray($hidden), $modelRelationsCode],
            $stub
        );

        file_put_contents("$base_path/$name.php", $stub);

        return true;
    }

    // =========================>
    // ## Light Migration Generation
    // =========================>
    private function migrationGeneration(string $model, array $schema = [])
    {
        $name = Str::snake(Str::pluralStudly($model));
        $base_path = 'database/migrations';
        $filename =  '0000_create_' . $name;

        if (file_exists("$base_path/$filename.php")) {
            unlink("$base_path/$filename.php");
        }

        $migrationFields = [];

        foreach ($schema as $column => $definition) {
            preg_match('/type:(\w+),?(\d+)?/', $definition, $typeMatch);
            $type = $typeMatch[1] ?? 'string';
            $length = $typeMatch[2] ?? null;

            $columnDefinition = match ($type) {
                'bigInteger' => "\$table->unsignedBigInteger('$column')",
                'integer' => "\$table->integer('$column')",
                'string' => $length ? "\$table->string('$column', $length)" : "\$table->string('$column')",
                'text' => "\$table->text('$column')",
                default => "\$table->$type('$column')",
            };

            if (str_contains($definition, 'foreignIdFor')) {
                preg_match('/foreignIdFor:(\w+),?(\d+)?/', $definition, $foreign);
                $columnDefinition .= "->foreignIdFor(App\Models\\$foreign[1]::class" . (isset($foreign[2]) ? ", $foreign[2]" : '') . ")";
            }
            if (str_contains($definition, 'unique')) {
                $columnDefinition .= "->unique()";
            }
            if (!str_contains($definition, 'required')) {
                $columnDefinition .= "->nullable()";
            }
            if (str_contains($definition, 'index')) {
                $columnDefinition .= "->index()";
            }

            $migrationFields[] = $columnDefinition . ";";
        }

        $stub = file_get_contents(resource_path('stubs/light-migration.stub'));

        $migrationSchema = implode("\n            ", $migrationFields);

        $stub = str_replace(
            ['{{ name }}', '{{ schemas }}'],
            [$name, $migrationSchema],
            $stub
        );

        file_put_contents("$base_path/$filename.php", $stub);

        return true;
    }

    // =========================>
    // ## Light Controller Generation
    // =========================>
    private function controllerGeneration(string $model, array $schema = [], array $relations = [], string $initial_name = "", string $route = "")
    {
        $base_path = 'app/Http/Controllers';

        if ($initial_name === '' || is_null($initial_name) || empty($initial_name)) {
            $initial_name = $model . 'Controller';
        }

        $names = explode('/', $initial_name);
        $name = $names[count($names)-1];
        array_pop($names);
        $folder = implode('/', $names);

        if (file_exists("$base_path/$initial_name.php")) {
            unlink("$base_path/$initial_name.php");
        }

        if (!file_exists("$base_path/$folder")) {
            mkdir("$base_path/$folder", 0775, true);
        }

        $validations = [];
        $tableName = Str::snake(Str::pluralStudly($model));

        foreach ($schema as $column => $rules) {
            preg_match("/type:(\w+)/", $rules, $typeMatch);
            $type = $typeMatch[1] ?? 'string';

            $validationRules = [];

            if (str_contains($rules, 'required')) {
                $validationRules[] = 'required';
            } else {
                $validationRules[] = 'nullable';
            }

            switch ($type) {
                case 'bigInteger':
                case 'integer':
                    $validationRules[] = 'number';
                    break;
                case 'string':
                    $validationRules[] = 'string';
                    preg_match("/type:string,(\d+)/", $rules, $lengthMatch);
                    if (!empty($lengthMatch[1])) {
                        $validationRules[] = "max:{$lengthMatch[1]}";
                    }
                    break;
            }

            if (str_contains($rules, 'unique')) {
                $validationRules[] = "unique:$tableName,$column";
            }

            $validations[$column] = implode('|', $validationRules);
        }

        $renderValidation = "\n" . implode("\n", array_map(function($column, $validation) {
            return '            "'.$column.'" => "'.$validation.'",';
        }, array_keys($validations), array_values($validations))) .  "\n        ";

        $withRelations = [];

        foreach ($relations as $relationName => $relationType) {
            $withRelations[] = "'$relationName'";
        }

        $renderWith = "->with([" . implode(", ", $withRelations) . "])";

        $stub = file_get_contents(resource_path('stubs/light-controller.stub'));

        $stub = str_replace(
            ['{{ namespace }}', '{{ name }}', '{{ model }}', '{{ validations }}', '{{ with }}'],
            [$folder ? "\\" . $folder : "", $name, $model, $renderValidation, $renderWith],
            $stub
        );

        file_put_contents("$base_path/$initial_name.php", $stub);

        $routeFile = base_path('routes/api.php');
        $route = $router ?? Str::slug(Str::snake(Str::pluralStudly(str_replace('Controller', '', $name)), '-'));
        $path = str_replace('/',"\\", $initial_name);
        $renderRoute = "Route::apiResource('$route', \\App\\Http\\Controllers\\$path::class);";

        $fileContent = file_get_contents($routeFile);

        if (!str_contains($fileContent, $renderRoute)) {
            $fileContent .= $renderRoute . "\n";

            file_put_contents($routeFile, $fileContent);
        }

        return true;
    }
}
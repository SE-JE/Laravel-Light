<?php

namespace App\Blueprints;

use App\Helpers\LightGenerationHelper;

class StarterBLueprint
{
    use LightGenerationHelper;
    /**
     * generation the application's entity.
     */
    public function run(): void
    {
        $this->blueprint([
            [
                "model" => "FeatureGroup",
                "schema" => [
                    "name" => "type:string,20 unique required fillable searchable selectable",
                ],
                'relations' => [
                    'features' => '[]Feature',
                ],
                "controllers" => [
                    "group-features" => "Feature/GroupFeatureController",
                ],
            ],
            [
                "model" => "Feature",
                "schema" => [
                    "group_feature_id" => 'type:bigInteger foreignIdFor:FeatureGroup fillable selectable index',
                    "code" => "type:string,3 unique required fillable searchable selectable index",
                    "name" => "type:string,20 unique required fillable searchable selectable",
                    "description" => "type:string,255 fillable searchable selectable index",
                ],
                'relations' => [
                    'accesses' => '[]FeatureAccess',
                    'group' => 'FeatureGroup,group_feature_id,id',
                ],
                "controllers" => [
                    "features" => "Feature/FeatureController",
                ],
            ],
            [
                "model" => "FeatureAccess",
                "schema" => [
                    "feature_id" => 'type:bigInteger foreignIdFor:Feature index',
                    "code" => "type:string maxLength:2 unique required fillable searchable selectable index",
                    "name" => "type: string maxLength:20 required fillable searchable selectable",
                ],
                'relations' => [
                    'feature' => 'Feature',
                ],
                "controllers" => [
                    "feature-accesses" => "Feature/FeatureAccessController",
                ],
            ],
        ]);
    }
}

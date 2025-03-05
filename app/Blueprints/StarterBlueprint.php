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
                'seeders' => [
                    ['Dashboard'], 
                    ['Pengguna'],
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
                'seeders' => [
                    [1, '001', 'Dashboard', 'Halaman Dashboard'],
                    [2, '002', 'Manajemen Pengguna', 'Halaman Manajemen Pengguna'],
                    [2, '003', 'Manajemen Role', 'Halaman Manajemen Role'],
                ],
            ],
            [
                "model" => "FeatureAccess",
                "schema" => [
                    "feature_id" => 'type:bigInteger foreignIdFor:Feature index',
                    "code" => "type:string maxLength:2 required fillable searchable selectable index",
                    "name" => "type: string maxLength:20 required fillable searchable selectable",
                ],
                'relations' => [
                    'feature' => 'Feature',
                ],
                "controllers" => [
                    "feature-accesses" => "Feature/FeatureAccessController",
                ],
                'seeders' => [
                    [1, '1', 'Melihat'],
                    [2, '1', 'Melihat'],
                    [2, '2', 'Membuat'],
                    [2, '3', 'Mengubah'],
                    [2, '4', 'Menghapus'],
                    [3, '1', 'Melihat'],
                    [3, '2', 'Mengubah'],
                ],
            ],
        ]);
    }
}

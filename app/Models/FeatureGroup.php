<?php

namespace App\Models;

use App\Helpers\LightModelHelper;
use Illuminate\Database\Eloquent\Model;

class FeatureGroup extends Model
{
    use LightModelHelper;

    // =========================>
    // ## Fillable
    // =========================>
    protected $fillable = [
        "name",
    ];

    // =========================>
    // ## Hidden
    // =========================>
    protected $hidden = [];

    // =========================>
    // ## Searchable
    // =========================>
    public $searchable = [
        "name",
    ];

    // =========================>
    // ## Selectable
    // =========================>
    public $selectable = [
        "id",
        "name",
    ];


    // =========================>
    // ## Relations
    // =========================>
    public function features() {
        return $this->hasMany(Feature::class);
    }

}

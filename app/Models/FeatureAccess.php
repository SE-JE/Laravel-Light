<?php

namespace App\Models;

use App\Helpers\LightModelHelper;
use Illuminate\Database\Eloquent\Model;

class FeatureAccess extends Model
{
    use LightModelHelper;

    // =========================>
    // ## Fillable
    // =========================>
    protected $fillable = [
        "code",
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
        "code",
        "name",
    ];

    // =========================>
    // ## Selectable
    // =========================>
    public $selectable = [
        "id",
        "code",
        "name",
    ];


    // =========================>
    // ## Relations
    // =========================>
    public function feature() {
        return $this->belongsTo(Feature::class);
    }

}

<?php

namespace App\Models;

use App\Helpers\LightModelHelper;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use LightModelHelper;

    // =========================>
    // ## Fillable
    // =========================>
    protected $fillable = [
        "group_feature_id",
        "code",
        "name",
        "description",
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
        "description",
    ];

    // =========================>
    // ## Selectable
    // =========================>
    public $selectable = [
        "id",
        "group_feature_id",
        "code",
        "name",
        "description",
    ];


    // =========================>
    // ## Relations
    // =========================>
    public function accesses() {
        return $this->hasMany(FeatureAccess::class);
    }

    public function group() {
        return $this->belongsTo(FeatureGroup::class, 'group_feature_id', 'id');
    }

}

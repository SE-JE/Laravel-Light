<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // =========================>
    // ## Fillable
    // =========================>
    protected $fillable = [
        'name',
        'email',
        'password',
        'picture_source',
        'phone',
        'verified_at',
    ];

    // =========================>
    // ## Hidden
    // =========================>
    protected $hidden = [
        'password',
        'remember_token',
        'last_active_at',
    ];

    // =========================>
    // ## Searchable
    // =========================>
    public $searchable = [
        'users.name',
        'users.email',
    ];

    // =========================>
    // ## Selectable
    // =========================>
    public $selectable = [
        'users.id',
        'users.name',
        'users.email',
    ];
}

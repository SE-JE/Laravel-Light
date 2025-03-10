<?php

namespace App\Models;

use App\Helpers\LightModelHelper;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LightModelHelper;

    // =========================>
    // ## Fillable
    // =========================>
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // =========================>
    // ## Hidden
    // =========================>
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // =========================>
    // ## Searchable
    // =========================>
    public $searchable = [
        'name',
        'email',
    ];

    // =========================>
    // ## Selectable
    // =========================>
    public $selectable = [
        'id',
        'name',
        'email',
        'crated_at',
    ];
}

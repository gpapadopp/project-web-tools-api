<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class users extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    public $timestamps = true;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'username',
        'password',
        'role_id',
        'locked',
        'disabled'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'locked' => 'boolean',
        'disabled' => 'boolean'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}

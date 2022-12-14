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
        'disabled',
        'token'
    ];

    protected $hidden = [
        'password',
        'token'
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
        return [
            'user_details' => [
                'id' => $this->id,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'role_id' => $this->role_id
            ]
        ];
    }

    public function role(){
        return $this->belongsTo(roles::class, 'role_id');
    }
}

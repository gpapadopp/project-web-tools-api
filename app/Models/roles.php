<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'create_right',
        'read_right',
        'update_right',
        'delete_right',
        'super_admin',
        'disabled',
    ];

    protected $hidden = [];

    protected $casts = [
        'create_right' => 'boolean',
        'read_right' => 'boolean',
        'update_right' => 'boolean',
        'delete_right' => 'boolean',
        'super_admin' => 'boolean',
        'disabled' => 'boolean'
    ];
}

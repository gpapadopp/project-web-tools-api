<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course_types extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'disabled',
    ];

    protected $hidden = [];

    protected $casts = [
        'disabled' => 'boolean'
    ];
}

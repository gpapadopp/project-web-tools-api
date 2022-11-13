<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class evaluations extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'token',
        'is_done',
        'course_id',
        'average',
        'user_id'
    ];

    protected $hidden = [
        'user_id'
    ];

    protected $casts = [
        'is_done' => 'boolean'
    ];

    public function user(){
        return $this->belongsTo(users::class, 'user_id');
    }

    public function course(){
        return $this->belongsTo(courses::class, 'course_id');
    }
}

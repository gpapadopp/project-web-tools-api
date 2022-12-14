<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courses extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'course_type_id',
    ];

    protected $hidden = [];

    protected $casts = [];

    public function course_type(){
        return $this->belongsTo(course_types::class, 'course_type_id');
    }

    public function user(){
        return $this->belongsTo(users::class, 'user_id');
    }
}

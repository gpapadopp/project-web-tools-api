<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class evaluations_meta extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'evaluation_id',
        'meta_key',
        'meta_value'
    ];

    protected $hidden = [];

    protected $casts = [];

    public function evaluation(){
        return $this->belongsTo(evaluations::class, 'evaluation_id');
    }
}

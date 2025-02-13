<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    protected $fillable = [
        'introduction',
        'experience',
        'skills',
        'education',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

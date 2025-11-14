<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Galery;
use App\Models\Dislike;

class Foto extends Model
{
    protected $table = 'foto';
    public $timestamps = true;
    
    protected $fillable = [
        'galery_id', 'file', 'likes', 'dislikes'
    ];

    public function galery()
    {
        return $this->belongsTo(Galery::class, 'galery_id');
    }
    
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function dislikes()
    {
        return $this->hasMany(Dislike::class);
    }
}
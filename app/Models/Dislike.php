<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dislike extends Model
{
    protected $table = 'user_dislikes';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'foto_id',
    ];

    public function foto()
    {
        return $this->belongsTo(Foto::class);
    }
}

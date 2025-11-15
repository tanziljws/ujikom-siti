<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryLikeLog extends Model
{
    protected $table = 'gallery_like_logs';

    protected $fillable = [
        'user_id',
        'foto_id',
        'action',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function foto(): BelongsTo
    {
        return $this->belongsTo(Foto::class);
    }
}

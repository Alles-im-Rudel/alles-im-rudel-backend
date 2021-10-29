<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstagramImage extends Model
{
    public $fillable = [
        'instagram_id',
        'instagram_post_id',
        'image_url'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instagramPost(): BelongsTo
    {
        return $this->belongsTo(InstagramPost::class);
    }
}

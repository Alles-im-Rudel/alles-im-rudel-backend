<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstagramProfile extends Model
{
    public $fillable = [
        'instagram_id',
        'name',
        'display_name',
        'image_url',
        'description',
        'description_url'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instagramPosts(): HasMany
    {
        return $this->hasMany(InstagramPost::class);
    }
}

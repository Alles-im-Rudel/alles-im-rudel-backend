<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class MinecraftPlayer extends Model
{
    use BelongsToUser;

    protected $fillable = [
        'name'
    ];
}

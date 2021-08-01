<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
	protected $fillable = [
		'name',
		'color'
	];

	/**
	 * @return BelongsToMany
	 */
	public function posts(): BelongsToMany
	{
		return $this->belongsToMany(Post::class);
	}
}

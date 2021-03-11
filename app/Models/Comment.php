<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
	use BelongsToUser;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'text',
		'post_id'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'post_id' => 'integer'
	];

	/**
	 * @return BelongsTo
	 */
	public function post(): BelongsTo
	{
		return $this->belongsTo(Post::class);
	}
}

<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
		'commentable_type',
		'commentable_id'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'commentable_id' => 'integer'
	];

	/**
	 * @return MorphTo
	 */
	public function commentable(): MorphTo
	{
		return $this->morphTo();
	}

	/**
	 * @return MorphMany
	 */
	public function comments(): MorphMany
	{
		return $this->morphMany(__CLASS__, 'commentable')->with(['comments', 'user.thumbnail']);
	}
}

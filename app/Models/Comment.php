<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Comment extends Model
{
	use BelongsToUser,
		CascadesDeletes;

	protected $cascadeDeletes = ['comments'];

	protected array $fillable = [
		'text',
		'commentable_type',
		'commentable_id'
	];

	protected $casts = [
		'commentable_id' => 'integer'
	];

	protected $appends = [
		'commentCount'
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

	public function getCommentCountAttribute(): int
	{
		$count = 0;
		if (count($this->comments) > 0) {
			$count = $this->comments->count();
			foreach ($this->comments as $comment) {
				$count += $comment->commentCount;
			}
		}
		return $count;
	}
}

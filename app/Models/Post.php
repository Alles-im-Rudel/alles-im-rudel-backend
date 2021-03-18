<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Post extends Model
{
	use BelongsToUser,
		CascadesDeletes;

	protected array $cascadeDeletes = ['comments', 'images'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'title',
		'text'
	];

	protected $appends = [
		'commentCount'
	];

	/**
	 * @return MorphMany
	 */
	public function thumbnails(): MorphMany
	{
		return $this->morphMany(Image::class, 'imageable')
			->select([
				'id',
				'imageable_id',
				'imageable_type',
				'thumbnail',
				'file_name',
				'file_size',
				'title',
				'file_mime_type'
			]);
	}

	/**
	 * @return MorphMany
	 */
	public function images(): MorphMany
	{
		return $this->morphMany(Image::class, 'imageable')
			->select([
				'id',
				'imageable_id',
				'imageable_type',
				'image',
				'title',
				'file_name',
				'file_size',
				'file_mime_type'
			]);
	}

	/**
	 * @return MorphMany
	 */
	public function comments(): MorphMany
	{
		return $this->morphMany(Comment::class, 'commentable');
	}

	/**
	 * @return BelongsToMany
	 */
	public function tags(): BelongsToMany
	{
		return $this->belongsToMany(Tag::class);
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

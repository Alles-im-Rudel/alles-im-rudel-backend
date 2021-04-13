<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use App\Traits\Relations\MorphManyLikes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Post extends Model
{
	use BelongsToUser,
		CascadesDeletes,
		MorphManyLikes;

	protected array $cascadeDeletes = ['comments', 'images', 'likes'];

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
	 * @return MorphToMany
	 */
	public function tags(): MorphToMany
	{
		return $this->morphToMany(Tag::class, 'tagable', 'model_tag');
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

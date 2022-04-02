<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use App\Traits\Relations\MorphManyLikes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Post extends Model
{
	use BelongsToUser,
        MorphManyLikes,
        CascadesDeletes;

	protected $cascadeDeletes = ['comments', 'image', 'thumbnail'];

	protected $fillable = [
		'title',
		'text',
        'tag_id',
	];

	/**
	 * @return MorphOne
	 */
	public function thumbnail(): MorphOne
	{
		return $this->morphOne(Image::class, 'imageable')
			->select([
				'id',
				'imageable_id',
				'imageable_type',
				'thumbnail',
				'file_name',
				'file_size',
				'file_mime_type'
			]);
	}

	/**
	 * @return MorphOne
	 */
	public function image(): MorphOne
	{
		return $this->morphOne(Image::class, 'imageable')
			->select([
				'id',
				'imageable_id',
				'imageable_type',
				'image',
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
	 * @return BelongsTo
	 */
	public function tag(): BelongsTo
	{
		return $this->belongsTo(Tag::class);
	}
}

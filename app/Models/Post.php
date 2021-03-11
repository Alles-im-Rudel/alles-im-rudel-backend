<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
	use BelongsToUser;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'title',
		'text'
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
	 * @return HasMany
	 */
	public function comments(): HasMany
	{
		return $this->hasMany(Comment::class);
	}

	/**
	 * @return BelongsToMany
	 */
	public function tags(): BelongsToMany
	{
		return $this->belongsToMany(Tag::class);
	}
}

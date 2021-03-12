<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'imageable_type',
		'imageable_id',
		'image',
		'thumbnail',
		'file_name',
		'file_size',
		'file_mime_type',
		'title'
	];

	protected $casts = [
		'id'           => 'integer',
		'imageable_id' => 'integer',
		'sort_order'   => 'integer',
		'file_size'    => 'integer'
	];

	/**
	 * @return MorphTo
	 */
	public function imageable(): MorphTo
	{
		return $this->morphTo();
	}
}

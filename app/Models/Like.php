<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends Model
{
	use BelongsToUser;

	protected $table = 'model_like';

	protected $fillable = [
		'likable_type',
		'likable_id',
	];

	protected $casts = [
		'likable_type' => 'integer',
		'likable_id'   => 'integer',
	];

	/**
	 * @return MorphTo
	 */
	public function likable(): MorphTo
	{
		return $this->morphTo();
	}
}

<?php

namespace App\Traits\Relations;

use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait MorphManyLikes
{
	public function initializeMorphManyLikes(): void
	{
		//Nothing to Initialize
	}

	/**
	 * @return MorphMany
	 */
	public function likes(): MorphMany
	{
		return $this->morphMany(Like::class, 'likeable', 'likeable_type');
	}
}

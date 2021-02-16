<?php

namespace App\Traits\Relations;

use App\Models\UserGroup;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait BelongsToManyUserGroups
{
	public function initializeBelongsToManyUserGroups(): void
	{
		//Nothing to Initialize
	}

	/**
	 * @return BelongsToMany
	 */
	public function userGroups(): BelongsToMany
	{
		return $this->belongsToMany(UserGroup::class);
	}
}

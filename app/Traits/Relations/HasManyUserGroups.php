<?php

namespace App\Traits\Relations;

use App\Models\Customer;
use App\Models\Newsletter;
use App\Models\Store;
use App\Models\UserGroup;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyUserGroups
{
	public function initializeHasManyUserGroups(): void
	{
		//Nothing to Initialize
	}

	/**
	 * @return HasMany
	 */
	public function userGroups(): HasMany
	{
		return $this->hasMany(UserGroup::class);
	}
}

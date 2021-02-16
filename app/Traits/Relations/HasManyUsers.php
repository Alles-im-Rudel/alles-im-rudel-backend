<?php

namespace App\Traits\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyUsers
{
	public function initializeHasManyUsers(): void
	{
		//Nothing to Initialize
	}

	/**
	 * @return HasMany
	 */
	public function users(): HasMany
	{
		return $this->hasMany(User::class);
	}
}

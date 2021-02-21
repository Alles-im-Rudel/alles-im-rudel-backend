<?php

namespace App\Traits\Relations;

use App\Models\Summoner;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait BelongsToManySummoners
{
	public function initializeBelongsToManySummoners(): void
	{
		//Nothing to Initialize
	}

	/**
	 * @return BelongsToMany
	 */
	public function summoners(): BelongsToMany
	{
		return $this->belogsToMany(Summoner::class);
	}
}

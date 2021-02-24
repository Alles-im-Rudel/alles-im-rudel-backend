<?php

namespace App\Traits\Relations;

use App\Models\Summoner;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToSummoner
{
	public function initializeBelongsToSummoner(): void
	{
		$this->fillable[] = 'summoner_id';
		$this->casts['summoner_id'] = 'integer';
	}

	/**
	 * @return BelongsTo
	 */
	public function summoner(): BelongsTo
	{
		return $this->belongsTo(Summoner::class);
	}
}

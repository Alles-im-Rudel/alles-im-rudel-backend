<?php

namespace App\Traits\Relations;

use App\Models\Level;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToLevel
{
	public function initializeBelongsToLevel(): void
	{
		$this->fillable[] = 'level_id';
		$this->casts['level_id'] = 'integer';
	}

	/**
	 * @return BelongsTo
	 */
	public function level(): BelongsTo
	{
		return $this->belongsTo(Level::class);
	}
}

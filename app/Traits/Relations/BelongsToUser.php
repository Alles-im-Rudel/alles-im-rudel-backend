<?php

namespace App\Traits\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToUser
{
	public function initializeBelongsToUser(): void
	{
		$this->fillable[] = 'user_id';
		$this->casts['user_id'] = 'integer';
	}

	/**
	 * @return BelongsTo
	 */
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}

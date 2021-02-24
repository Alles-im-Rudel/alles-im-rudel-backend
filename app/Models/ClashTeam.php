<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClashTeam extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'leader_id'
	];

	/**
	 * @return HasMany
	 */
	public function clashMembers(): HasMany
	{
		return $this->hasMany(ClashMember::class);
	}

	/**
	 * @return BelongsTo
	 */
	public function leader(): BelongsTo
	{
		return $this->belongsTo(User::class, 'leader_id', 'id');
	}
}

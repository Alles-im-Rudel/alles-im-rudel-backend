<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class ClashTeam extends Model
{
	use CascadesDeletes;

	protected $cascadeDeletes = ['clashMembers'];

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

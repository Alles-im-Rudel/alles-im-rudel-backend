<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{

	public const AIR = 1;
	public const AIRSOFT = 2;
	public const ESPORTS = 3;

	protected $fillable = [
		'name',
		'price',
		'description',
		'activated_at',
		'is_selectable'
	];

	protected $casts = [
		'price'         => 'float',
		'activated_at'  => 'datetime',
		'is_selectable' => 'boolean',
	];

	protected $appends = [
		'is_active'
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function branchUserMemberShips(): HasMany
	{
		return $this->hasMany(BranchUserMemberShip::class);
	}

	public function getIsActiveAttribute(): bool
	{
		return (bool) $this->activated_at;
	}
}

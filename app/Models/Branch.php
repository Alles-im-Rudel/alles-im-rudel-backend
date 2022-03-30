<?php

namespace App\Models;

use App\Traits\Relations\BelongsToLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Branch extends Model
{

	public const AIR = 1;
	public const AIRSOFT = 1;
	public const ESPORTS = 1;

	protected $fillable = [
		'name',
		'price',
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
	 * @return BelongsToMany
	 */
	public function members(): BelongsToMany
	{
		return $this->belongsToMany(MemberShip::class);
	}

	public function getIsActiveAttribute(): bool
	{
		return (bool) $this->activated_at;
	}
}

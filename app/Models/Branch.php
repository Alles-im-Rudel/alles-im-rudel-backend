<?php

namespace App\Models;

use App\Traits\Relations\BelongsToLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Branch extends Model
{

	protected $fillable = [
		'name',
		'price',
		'activated_at'
	];

	protected $casts = [
		'price'        => 'float',
		'activated_at' => 'datetime'
	];

	protected $appends = [
		'is_active'
	];

	/**
	 * @return BelongsToMany
	 */
	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class);
	}

	public function getIsActiveAttribute(): bool
	{
		return (bool) $this->activated_at;
	}
}

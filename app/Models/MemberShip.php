<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MemberShip extends Model
{
	use BelongsToUser;

	protected array $fillable = [
		'user_id',
		'country_id',
		'phone',
		'street',
		'postcode',
		'city',
		'iban',
		'activated_at',
	];

	protected $appends = [
		'is_active'
	];

	protected $casts = [
		'country_id'   => 'integer',
		'activated_at' => 'datetime',
	];

	/**
	 * @return BelongsToMany
	 */
	public function branches(): BelongsToMany
	{
		return $this->belongsToMany(Branch::class);
	}

	/**
	 * @return BelongsToMany
	 */
	public function contactTypes(): BelongsToMany
	{
		return $this->belongsToMany(ContactType::class);
	}

	/**
	 * @return BelongsTo
	 */
	public function country(): BelongsTo
	{
		return $this->belongsTo(Country::class);
	}

	public function getIsActiveAttribute(): bool
	{
		return (bool) $this->activated_at;
	}
}

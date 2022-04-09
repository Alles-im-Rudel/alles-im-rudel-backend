<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class MemberShip extends Model
{
	use BelongsToUser,
		CascadesDeletes;

	protected $cascadeDeletes = ['branches'];

	protected $fillable = [
		'user_id',
		'country_id',
		'phone',
		'street',
		'postcode',
		'salutation',
		'city',
		'iban',
		'bic',
		'activated_at',
	];

	protected $appends = [
		'is_active'
	];

	protected $casts = [
		'country_id'                        => 'integer',
		'activated_at'                      => 'datetime',
		'branches.pivot.created_at'         => 'datetime',
		'branches.pivot.updated_at'         => 'datetime',
		'branches.pivot.deleted_at'         => 'datetime',
		'branches.pivot.exported_at'        => 'datetime',
		'branches.pivot.wanted_to_leave_at' => 'datetime',
	];

	/**
	 * @return BelongsToMany
	 */
	public function branches(): BelongsToMany
	{
		return $this->belongsToMany(Branch::class)
			->whereNull('deleted_at')
			->withTimestamps()
			->withPivot(['id'])
			->withPivot(['activated_at'])
			->withPivot(['wanted_to_leave_at'])
			->withPivot(['exported_at'])
			->withPivot(['deleted_at']);
	}

	/**
	 * @return BelongsToMany
	 */
	public function branchesWithTrashed(): BelongsToMany
	{
		return $this->belongsToMany(Branch::class)
			->withTimestamps()
			->withPivot(['id'])
			->withPivot(['activated_at'])
			->withPivot(['wanted_to_leave_at'])
			->withPivot(['exported_at'])
			->withPivot(['deleted_at']);
	}

	/**
	 * @return MorphOne
	 */
	public function signature(): MorphOne
	{
		return $this->morphOne(Image::class, 'imageable')
			->select([
				'id',
				'imageable_id',
				'imageable_type',
				'image',
				'title',
				'file_name',
				'file_size',
				'file_mime_type'
			]);
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

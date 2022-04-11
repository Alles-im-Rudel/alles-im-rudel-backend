<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchUserMemberShip extends Model
{
	use BelongsToUser,
		SoftDeletes;

	protected $fillable = [
		'user_id',
		'branch_id',
		'activated_at',
		'wants_to_leave_at',
		'exported_at',
	];

	protected $appends = [
		'is_active',
		'wants_to_leave',
		'is_exported'
	];

	protected $casts = [
		'branch_id'         => 'integer',
		'activated_at'      => 'datetime',
		'exported_at'       => 'datetime',
		'wants_to_leave_at' => 'datetime',
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function branche(): BelongsTo
	{
		return $this->belongsTo(Branch::class);
	}

	public function getIsActiveAttribute(): bool
	{
		return (bool) $this->activated_at;
	}

	public function getWantsToLeaveAttribute(): bool
	{
		return (bool) $this->wants_to_leave_at;
	}

	public function getIsExportedAttribute(): bool
	{
		return (bool) $this->exported_at;
	}
}

<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

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
		'is_exported',
		'sepa_date',
		'state'
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
	public function branch(): BelongsTo
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

	public function getSepaDateAttribute()
	{
		if($this->wantsToLeave) {
			return Carbon::parse($this->activated_at)->addMonth()->endOfMonth();
		}
		return Carbon::parse($this->activated_at)->addMonth()->startOfMonth();
	}

	public function getStateAttribute()
	{
		if ($this->is_active && $this->wants_to_leave) {
			return 'wantsToLeave';
		}

		if (!$this->is_active && !$this->wants_to_leave) {
			return 'wantsToJoin';
		}

		return 'isMember';
	}
}

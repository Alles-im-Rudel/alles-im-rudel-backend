<?php

namespace App\Models;

use App\Traits\Relations\BelongsToLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class UserGroup extends Model
{
	use SoftDeletes,
        HasRoles,
        BelongsToLevel;

	protected $guard_name = 'web';

	/*
	 * Admins
	 */
	public const DEVELOPER_ID = 1;
	public const ADMIN_ID = 2;

	/*
	 * Moderators
	 */
	public const MODERATOR_ID = 3;
	public const AIRSOFT_LEADER_ID = 4;
	public const E_SPORTS_LEADER_ID = 5;

	/*
	 * Members
	 */
	public const MEMBER_ID = 6;
	public const E_SPORTS_MEMBER_ID = 7;
	public const AIRSOFT_MEMBER_ID = 8;

	protected $fillable = [
		'display_name',
		'color',
		'description'
	];

	/**
	 * @return BelongsToMany
	 */
	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class);
	}
}

<?php

namespace App\Models;

use App\Traits\Relations\BelongsToLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class UserGroup extends Model
{
	use SoftDeletes, HasRoles, BelongsToLevel;

	protected string $guard_name = 'web';

	public const DEVELOPER_ID = 1;
	public const ADMIN = 2;
	public const MODERATOR = 3;
	public const MEMBER = 4;
	public const E_SPORTS = 5;
	public const AIRSOFT = 6;
	public const PROSPECT = 7;
	public const GUEST = 8;
	public const NEW = 11;

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

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

	protected $guard_name = 'web';

	public const DEVELOPER_ID = 1;
	public const ADMINISTRATOR_ID = 2;
	public const MODERATOR_ID = 3;
	public const MEMBER_ID = 4;

	protected $fillable = [
		'display_name'
	];

	/**
	 * @return BelongsToMany
	 */
	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class);
	}
}
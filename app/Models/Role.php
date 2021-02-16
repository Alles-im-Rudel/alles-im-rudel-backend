<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
	protected $fillable = [
		'name',
		'display_name',
		'guard_name'
	];

	protected $guard_name = 'web';

	public function userGroups(): BelongsToMany
	{
		return $this->morphedByMany(
			UserGroup::class,
			'model',
			config('permission.table_names.model_has_roles'),
			'role_id',
			config('permission.column_names.model_morph_key')
		);
	}
}

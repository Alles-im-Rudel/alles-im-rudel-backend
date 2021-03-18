<?php

namespace App\Models;

use App\Traits\Relations\BelongsToLevel;
use App\Traits\Relations\BelongsToManySummoners;
use App\Traits\Relations\BelongsToManyUserGroups;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
	use Notifiable,
		SoftDeletes,
		HasApiTokens,
		HasRoles,
		BelongsToLevel,
		BelongsToManyUserGroups,
		BelongsToManySummoners;

	public const DEVELOPER_ID = 1;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'first_name',
		'last_name',
		'username',
		'email',
		'activated_at',
		'email_verified_at',
		'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'activated_at'      => 'datetime'
	];

	/**
	 * @param  iterable|string  $permission
	 * @param  array  $arguments
	 * @return bool
	 */
	public function can($permission, $arguments = []): bool
	{
		$permissionCollection = collect([]);
		$permissionCollection = $permissionCollection->merge($this->getAllPermissions());
		foreach (Auth::user()->userGroups as $userGroup) {
			foreach ($userGroup->getAllPermissions() as $p) {
				if (!$permissionCollection->contains('id', $p->id)) {
					$permissionCollection = $permissionCollection->merge([$p]);
				}
			}
		}
		return $permissionCollection->where('name', $permission)->first() ? true : false;
	}

	/**
	 * @return mixed
	 */
	public function getMaxLevelId()
	{
		$userGroupMaxLevel = $this->userGroups()->max('level_id');

		return $userGroupMaxLevel > $this->level_id ? $userGroupMaxLevel : $this->level_id;
	}

	/**
	 * @return HasOne
	 */
	public function mainSummoner(): HasOne
	{
		return $this->hasOne(Summoner::class, 'main_user_id', 'id');
	}

	/**
	 * @return MorphOne
	 */
	public function thumbnail(): MorphOne
	{
		return $this->morphOne(Image::class, 'imageable')
			->select([
				'id',
				'imageable_id',
				'imageable_type',
				'title',
				'thumbnail',
				'file_name',
				'file_size',
				'file_mime_type'
			]);
	}

	/**
	 * @return MorphOne
	 */
	public function image(): MorphOne
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
}

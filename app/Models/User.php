<?php

namespace App\Models;

use App\Notifications\VerifyEmailNotification;
use App\Traits\Relations\BelongsToLevel;
use App\Traits\Relations\BelongsToManySummoners;
use App\Traits\Relations\BelongsToManyUserGroups;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
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

	protected $fillable = [
		'first_name',
		'last_name',
		'username',
		'email',
		'birthday',
		'activated_at',
		'level_id',
		'email_verified_at',
		'wants_email_notification',
		'password',
	];

	protected $appends = [
		'age'
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
		'email_verified_at'        => 'datetime',
		'wants_email_notification' => 'bool',
		'activated_at'             => 'datetime'
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
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeMember(Builder $query): Builder
	{
		return $query->where(static function (Builder $query) {
			$query->whereBetween('level_id', [Level::PROSPECT, Level::ADMINISTRATOR])
				->orWhere(static function (Builder $query) {
					$query->whereHas('userGroups', function ($query) {
						$query->whereBetween('level_id', [Level::PROSPECT, Level::ADMINISTRATOR]);
					});
				});
		});
	}

	/**
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeLevelScope(Builder $query): Builder
	{
		return $query->where(static function (Builder $query) {
			$query->where('level_id', '<=', Auth::user()->getMaxLevelId())
				->whereDoesntHave('userGroups', function ($query) {
					$query->where('level_id', '>', Auth::user()->getMaxLevelId());
				});
		});
	}

	public function scopeNotification(Builder $query): Builder
	{
		return $query->where('wants_email_notification', '=', true);
	}

	public function sendEmailVerificationNotification(): void
	{
		$this->notify(new VerifyEmailNotification());
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

	/**
	 * @param  Appointment  $appointment
	 * @return bool
	 */
	public function hasLikedAppointment(Appointment $appointment): bool
	{
		return (bool) $appointment->likes->where('user_id', $this->id)->count();
	}


	/**
	 * @param  Post  $post
	 * @return bool
	 */
	public function hasLikedPost(Post $post): bool
	{
		return (bool) $post->likes->where('user_id', $this->id)->count();
	}

	/**
	 * @return HasMany
	 */
	public function posts(): HasMany
	{
		return $this->hasMany(Post::class);
	}

	/**
	 * @return HasMany
	 */
	public function comments(): HasMany
	{
		return $this->hasMany(Comment::class);
	}

	/**
	 * @return HasMany
	 */
	public function liked(): HasMany
	{
		return $this->hasMany(Like::class, 'user_id', 'id');
	}

	public function getAgeAttribute(): int
	{
		return Carbon::now()->diffInYears(Carbon::parse($this->birthday));
	}

}

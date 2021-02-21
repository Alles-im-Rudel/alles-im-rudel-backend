<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Summoner extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'account_id',
		'profile_icon_id',
		'revision_date',
		'name',
		'summoner_id',
		'puuid',
		'summoner_level',
		'main_user_id',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'main_user_id'    => 'integer',
		'profile_icon_id' => 'integer',
		'revision_date'   => 'datetime'
	];

	/**
	 * @return BelongsToMany
	 */
	public function users(): BelongsToMany
	{
		return $this->belongsToMany(User::class);
	}

	/**
	 * @return BelongsTo
	 */
	public function mainUser(): BelongsTo
	{
		return $this->belongsTo(User::class, 'main_user_id', 'id');
	}
}

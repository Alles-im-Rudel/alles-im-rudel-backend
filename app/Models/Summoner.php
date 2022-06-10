<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Summoner extends Model
{
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

	/**
	 * @return HasMany
	 */
	public function leagueEntries(): HasMany
	{
		return $this->hasMany(LeagueEntry::class, 'summoner_id', 'summoner_id')->orderBy('queue_type','DESC');
	}
}

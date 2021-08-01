<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeagueEntry extends Model
{
	protected $fillable = [
		'league_id',
		'summoner_id',
		'queue_type',
		'tier',
		'rank',
		'league_points',
		'wins',
		'losses',
		'hot_streak',
		'veteran',
		'fresh_blood',
		'inactive',
	];

	protected $casts = [
		'league_points' => 'integer',
		'wins'          => 'integer',
		'losses'        => 'integer',
		'hot_streak'    => 'boolean',
		'veteran'       => 'boolean',
		'fresh_blood'   => 'boolean',
		'inactive'      => 'boolean',
	];

	/**
	 * @return BelongsTo
	 */
	public function summoner(): BelongsTo
	{
		return $this->belongsTo(Summoner::class, 'summoner_id', 'summoner_id');
	}

	/**
	 * @return BelongsTo
	 */
	public function queueType(): BelongsTo
	{
		return $this->belongsTo(QueueType::class, 'queue_type', 'queue_type');
	}
}

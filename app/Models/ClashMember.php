<?php

namespace App\Models;

use App\Traits\Relations\BelongsToSummoner;
use App\Traits\Relations\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClashMember extends Model
{
	use BelongsToUser,
		BelongsToSummoner;

	protected $fillable = [
		'clash_team_role_id',
		'clash_team_id',
		'is_active'
	];

	protected $casts = [
		'clash_team_role_id' => 'integer',
		'clash_team_id'      => 'integer',
		'is_active'          => 'boolean'
	];

	/**
	 * @return BelongsTo
	 */
	public function clashTeamRole(): BelongsTo
	{
		return $this->belongsTo(ClashTeamRole::class);
	}

	/**
	 * @return BelongsTo
	 */
	public function clashTeam(): BelongsTo
	{
		return $this->belongsTo(ClashTeam::class);
	}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiotSummonerSpell extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'key',
		'spell_id',
		'name',
		'description',
		'tooltip',
		'cooldown',
		'image',
	];
}

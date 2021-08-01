<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class RiotSummonerIcon extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'name',
		'image',
	];
}

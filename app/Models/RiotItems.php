<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiotItems extends Model
{
	public $timestamps = false;

	protected $fillable = [
		'item_id',
		'name',
		'description',
		'plaintext',
		'into',
		'from',
		'gold',
		'tags',
		'maps',
		'stats',
		'image',
	];
}

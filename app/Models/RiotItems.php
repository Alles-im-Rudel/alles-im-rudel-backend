<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiotItems extends Model
{
	public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
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

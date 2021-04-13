<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Champion extends Model
{
	public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'champion_id',
		'version',
		'key',
		'name',
		'title',
		'blurb',
		'info',
		'image',
		'icon',
		'splash_art',
		'tags',
		'partype',
		'stats',
	];

}

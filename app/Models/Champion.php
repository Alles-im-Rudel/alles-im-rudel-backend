<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Champion extends Model
{
	public $timestamps = false;

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

	public static function getDefault()
	{
		return self::where('key', '-1')->first();
	}
}

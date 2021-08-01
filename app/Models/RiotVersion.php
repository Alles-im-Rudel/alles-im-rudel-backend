<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiotVersion extends Model
{
	public static ?string $version = null;

	public $timestamps = false;

	protected $fillable = [
		'version',
	];

	/**
	 * @return null|string
	 */
	public static function getVersion(): ?string
	{
		if(!self::$version) {
			self::$version = self::select('version')->first()->version ?? null;
		}
		return self::$version;
	}
}

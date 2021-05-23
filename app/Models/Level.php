<?php

namespace App\Models;

use App\Traits\Relations\HasManyUserGroups;
use App\Traits\Relations\HasManyUsers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
	use SoftDeletes, HasManyUsers, HasManyUserGroups;

	public const DEVELOPER = 100;
	public const ADMINISTRATOR = 95;
	public const MODERATOR = 90;
	public const MEMBER = 50;
	public const PROSPECT = 25;
	public const GUEST = 1;

	protected $fillable = [
		'id',
		'display_name'
	];
}

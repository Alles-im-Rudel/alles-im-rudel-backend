<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueType extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'queue_type',
		'display_name',
	];
}

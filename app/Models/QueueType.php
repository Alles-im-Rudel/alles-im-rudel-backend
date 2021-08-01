<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QueueType extends Model
{
	protected $fillable = [
		'queue_type',
		'display_name',
	];
}

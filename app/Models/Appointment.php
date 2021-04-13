<?php

namespace App\Models;

use App\Traits\Relations\BelongsToUser;
use App\Traits\Relations\MorphManyLikes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class Appointment extends Model
{
	use BelongsToUser,
		CascadesDeletes,
		MorphManyLikes;

	protected array $cascadeDeletes = ['likes'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'title',
		'text',
		'start_at',
		'end_at',
		'color',
		'is_all_day',
		'is_birthday',
		'birthday_id',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'end_at'      => 'datetime',
		'start_at'    => 'datetime',
		'is_all_day'  => 'boolean',
		'is_birthday' => 'boolean',
		'birthday_id' => 'integer',
	];

	/**
	 * @return BelongsTo
	 */
	public function birthdayKid(): BelongsTo
	{
		return $this->belongsTo(User::class, 'birthday_id', 'id');
	}

	/**
	 * @return MorphToMany
	 */
	public function tags(): MorphToMany
	{
		return $this->morphToMany(Tag::class, 'tagable', 'model_tag');
	}
}

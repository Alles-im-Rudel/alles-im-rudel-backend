<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use ShiftOneLabs\LaravelCascadeDeletes\CascadesDeletes;

class BankAccount extends Model
{
	use CascadesDeletes;

	protected $cascadeDeletes = ['signature'];

	protected $fillable = [
		'country_id',
		'iban',
		'bic',
		'first_name',
		'last_name',
		'street',
		'postcode',
		'city',
		'signature_city',
	];

	protected $appends = [
		'full_name',
	];

	protected $casts = [
		'country_id' => 'integer',
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function country(): BelongsTo
	{
		return $this->belongsTo(Country::class);
	}

	public function getFullNameAttribute(): string
	{
		return $this->first_name.' '.$this->last_name;
	}

	/**
	 * @return MorphOne
	 */
	public function signature(): MorphOne
	{
		return $this->morphOne(Image::class, 'imageable')
			->select([
				'id',
				'imageable_id',
				'imageable_type',
				'image',
				'title',
				'file_name',
				'file_size',
				'file_mime_type'
			]);
	}
}

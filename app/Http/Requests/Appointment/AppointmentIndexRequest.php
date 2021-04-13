<?php

namespace App\Http\Requests\Appointment;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentIndexRequest extends FormRequest
{
	use RequestHelper;

	public function prepareForValidation(): void
	{
		$this->convertToString('search');
		$this->convertToInteger('year');
		$this->convertToInteger('month');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'search'   => 'nullable|string',
			'month'    => 'integer|integer',
			'year'     => 'integer|integer',
			'tagIds'   => 'nullable|array',
			'tagIds.*' => 'integer|exists:tags,id'
		];
	}
}

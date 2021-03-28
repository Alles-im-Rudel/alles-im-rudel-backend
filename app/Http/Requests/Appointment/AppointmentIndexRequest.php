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
			'tagIds'   => 'nullable|array',
			'tagIds.*' => 'integer|exists:tags,id'
		];
	}
}

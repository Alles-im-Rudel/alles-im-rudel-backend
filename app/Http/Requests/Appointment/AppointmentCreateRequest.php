<?php

namespace App\Http\Requests\Appointment;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AppointmentCreateRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('appointments.create');
	}

	public function prepareForValidation(): void
	{
		$this->convertToCarbonDate('startAt');
		$this->convertToCarbonDate('endAt');
		$this->convertToBoolean('isAllDay');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'title'    => 'required|string',
			'text'     => 'required|string',
			'color'    => 'required|string',
			'tags'     => 'nullable|array',
			'tags.*'   => 'nullable|integer|exists:appointments,id',
			'startAt'  => 'required|date',
			'endAt'    => 'nullable|date',
			'isAllDay' => 'required|boolean'
		];
	}
}

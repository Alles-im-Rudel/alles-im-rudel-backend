<?php

namespace App\Http\Requests\Appointment;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AppointmentIndexRequest extends FormRequest
{
    use RequestHelper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

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

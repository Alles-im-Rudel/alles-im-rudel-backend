<?php

namespace App\Http\Requests\Member;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;

class SEPAIndexRequest extends FormRequest
{
	use RequestHelper;

	public function prepareForValidation(): void
	{

	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [];
	}
}

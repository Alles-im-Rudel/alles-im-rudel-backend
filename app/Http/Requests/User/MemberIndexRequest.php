<?php

namespace App\Http\Requests\User;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;

class MemberIndexRequest extends FormRequest
{
	use RequestHelper;

	public function prepareForValidation(): void
	{
		$this->convertToInteger('page');
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
			'page'   => 'required|integer',
			'search' => 'nullable|string',
		];
	}
}

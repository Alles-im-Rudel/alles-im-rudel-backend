<?php

namespace App\Http\Requests\Auth;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateBranchesRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return true;
	}

	public function prepareForValidation(): void
	{
		$this->addKeyIfNotExist('branchIds');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'branchIds'   => 'nullable|array',
			'branchIds.*' => 'integer|exists:branches,id'
		];
	}
}

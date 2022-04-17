<?php

namespace App\Http\Requests\MemberShipApplication;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ManageMemberShipApplicationIndexRequest extends FormRequest
{
	use RequestHelper;


	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('members.mamage');
	}

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

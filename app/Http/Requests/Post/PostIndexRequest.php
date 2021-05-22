<?php

namespace App\Http\Requests\Post;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;

class PostIndexRequest extends FormRequest
{
	use RequestHelper;

	public function prepareForValidation(): void
	{
		$this->convertToInteger('page');
		$this->convertToString('search');
		$this->convertToInteger('items');
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules(): array
	{
		return [
			'page'     => 'required|integer',
			'search'   => 'nullable|string',
			'tagIds'   => 'nullable|array',
			'items'    => 'nullable|integer',
			'tagIds.*' => 'integer|exists:tags,id'
		];
	}
}

<?php

namespace App\Http\Requests\Post;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostUpdateRequest extends FormRequest
{
	use RequestHelper;

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize(): bool
	{
		return Auth::user()->can('posts.update');
	}

	public function prepareForValidation(): void
	{
		$this->convertToString('title');
		$this->convertToString('text');
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
			'tagIds'   => 'nullable|array',
			'tagIds.*' => 'integer|exists:tags,id'
		];
	}
}

<?php

namespace App\Http\Requests\Auth;

use App\Traits\Requests\RequestHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
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
        $this->convertToString('email');
        $this->convertToString('salutation');
        $this->convertToString('firstName');
        $this->convertToString('lastName');
        $this->convertToString('phone');
        $this->convertToString('password');
        $this->convertToString('passwordRepeat');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $id = Auth::id();
        return [
            'email'          => 'required|email:dns,rfc|unique:users,email,' . $id,
            'salutation'     => 'required|string',
            'firstName'      => 'required|string',
            'lastName'       => 'required|string',
            'phone'          => 'required|string',
            'password'       => 'nullable|string',
            'passwordRepeat' => 'nullable|string',
        ];
    }
}

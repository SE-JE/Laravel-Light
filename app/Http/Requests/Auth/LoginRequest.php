<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'string', 'max:255', Rule::exists('users', 'email')],
            'password' => ['required', 'string'],
            'remember_me' => ['boolean'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.exists' => __('The :attribute does not exist.'),
            'email.required' => __('The :attribute is required.'),
            'email.email' => __('The :attribute must be a valid email address.'),
            'email.string' => __('The :attribute must be a string.'),
            'email.max' => __('The :attribute must not be greater than :max characters.'),
            'password.required' => __('The :attribute is required.'),
            'password.string' => __('The :attribute must be a string.'),
            'remember_me.boolean' => __('The :attribute must be true or false.'),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'email' => __('email address'),
            'password' => __('password'),
            'remember_me' => __('remember me'),
        ];
    }
}

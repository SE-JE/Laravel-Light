<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

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
            'name.required' => __('The :attribute is required.'),
            'name.string' => __('The :attribute must be a string.'),
            'name.max' => __('The :attribute must not be greater than :max characters.'),
            'email.required' => __('The :attribute is required.'),
            'email.email' => __('The :attribute must be a valid email address.'),
            'email.string' => __('The :attribute must be a string.'),
            'email.max' => __('The :attribute must not be greater than :max characters.'),
            'email.unique' => __('The :attribute has already been taken.'),
            'password.required' => __('The :attribute is required.'),
            'password.string' => __('The :attribute must be a string.'),
            'password.min' => __('The :attribute must be at least :min characters.'),
            'password.confirmed' => __('The :attribute confirmation does not match.'),
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
            'name' => __('name'),
            'email' => __('email address'),
            'password' => __('password'),
        ];
    }
}

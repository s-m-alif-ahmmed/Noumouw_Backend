<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'date_of_birth' => 'required|date|before:today|date_format:Y-m-d',
            'parent_role' => 'required|in:father,mother',
            'is_parent' => 'required|boolean',
            'country'  => 'required|string',
            'children' => 'nullable|array',
            'children.*.name' => 'nullable',
            'children.*.date_of_birth' => 'nullable|date|before:today|date_format:Y-m-d',
        ];
    }
}

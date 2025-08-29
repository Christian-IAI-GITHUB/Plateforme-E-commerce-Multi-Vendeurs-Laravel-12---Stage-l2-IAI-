<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
        'current_pwd' => ['required', 'string', 'min:6'],
        'new_pwd' => ['required', 'string', 'min:6', 'different:current_pwd'],
        'confirm_pwd' => ['required', 'string', 'same:new_pwd']
        ];
    }

        public function messages()
    {
        return [
            'current_pwd.required' => 'Le mot de passe actuel est requis.',
            'current_pwd.min' => 'Le mot de passe actuel doit comporter au moins 6 caractères.',
            'new_pwd.required' => 'Un nouveau mot de passe est requis.',
            'new_pwd.min' => 'Le nouveau mot de passe doit comporter au moins 6 caractères.',
            'new_pwd.different' => 'Le nouveau mot de passe doit être différent de actuel mot de passe.',
            'confirm_pwd.required' => 'La confirmation du mot de passe est requise.',
            'confirm_pwd.same' => 'La confirmation du mot de passe doit correspondre au nouveau mot de passe.'
        ];
    }

}

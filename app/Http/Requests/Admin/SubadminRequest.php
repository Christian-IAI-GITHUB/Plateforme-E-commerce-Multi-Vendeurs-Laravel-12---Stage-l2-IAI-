<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Admin;


class SubadminRequest extends FormRequest
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
            'name' => 'required',
            'mobile' => 'required|numeric',
            'image' => 'image',
            'email' => 'required|email'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom est obligatoire',
            'mobile.required' => 'Le numéro de téléphone est obligatoire',
            'mobile.numeric' => 'Un numéro de téléphone valide est requis',
            'image.image' => 'Une image valide est requise',
            'email.required' => 'L\'adresse e-mail est obligatoire',
            'email.email' => 'Une adresse e-mail valide est requise'
        ];

    }

    public function withValidator($validator){
        $validator->after(function ($validator) {
            if ($this->input('id') == "") {
                $subadminCount = Admin::where('email', $this->input('email'))->count();
                if ($subadminCount > 0) {
                    $validator->errors()->add('email', 'Le sous-administrateur existe déjà !');
                }
            }
        });
    }

    protected function failedValidation(Validator $validator){
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }

}

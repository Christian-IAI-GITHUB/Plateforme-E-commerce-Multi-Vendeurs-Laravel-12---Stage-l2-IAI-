<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DetailRequest extends FormRequest
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
            'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
            'mobile' => 'required|numeric|digits_between:10,13',  
            'image' => 'image'
        ];
    }
        public function messages(){
        return [
            'name.required' => "Le nom est requis",
            'name.regex' => "Un nom valide est requis",
            'name.max' => "Un nom valide est requis",
            'mobile.required' => 'Le mobile est requis',
            'mobile.numeric' => 'Un mobile valide est requis',
            'mobile.digits' => 'Un mobile valide est requis',  
            'image.image' => 'Une image valide est requise' 
        ];
    }

}

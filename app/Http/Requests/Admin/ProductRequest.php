<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'category_id' => 'required',
            'brand_id' => 'required',
            'product_name' => 'required|max:200',
            'product_code' => 'required|max:30',
            'product_price' => 'required|numeric|gt:0',
            'product_color' => 'required|max:200',
            'family_color' => 'required|max:200'
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'La catégorie est obligatoire',
            'brand_id.required' => 'La marque est obligatoire',
            'product_name.required' => 'Le nom du produit est obligatoire',
            'product_code.required' => 'Le code du produit est obligatoire',
            'product_price.required' => 'Le prix du produit est obligatoire',
            'product_price.numeric' => 'Le prix du produit doit être un nombre valide',
            'product_color.required' => 'La couleur du produit est obligatoire',
            'family_color.required' => 'La couleur de la famille est obligatoire'
        ];

    }

}

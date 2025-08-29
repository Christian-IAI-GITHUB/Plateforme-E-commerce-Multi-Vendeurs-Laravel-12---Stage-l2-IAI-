<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Brand;

class BrandRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'url' => 'required|regex:/^[\pL\s\-]+$/u'
        ];
    }
    
    /**
     * Custom error messages for validation.
     */
    public function messages()
    {
        return [
            'name.required' => 'Le nom de la marque est requis',
            'url.required' => 'URL de la marque est requise',
        ];
    }
    
     /**
     * Prepare request before validation.
     */
    protected function prepareForValidation()
    {
        if ($this->route('brand')) {
            $this->merge([
                'id' => $this->route('brand'),
            ]);
        }
    }
    
     /**
     * Custom validator logic for checking URL uniqueness.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $brandCount = Brand::where('url', $this->input('url'));
    if ($this->filled('id')) {
                $brandCount->where('id', '!=', $this->input('id'));
            }
    if ($brandCount->count() > 0) {
                $validator->errors()->add('url', 'Brand already exists!');
            }
        });
    }
    
     /**
     * Customize validation failure response.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}

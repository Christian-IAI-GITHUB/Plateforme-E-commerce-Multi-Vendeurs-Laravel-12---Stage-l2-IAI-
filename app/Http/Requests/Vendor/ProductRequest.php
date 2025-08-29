<?php

namespace App\Http\Requests\Vendor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Vérifier que l'utilisateur est connecté et qu'il est vendeur
        return Auth::check() && Auth::user()->role === 1;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $productId = $this->route('product') ? $this->route('product')->id : null;
        
        return [
            // Informations de base
            'product_name' => 'required|string|max:255',
            'product_code' => 'required|string|max:100|unique:products,product_code,' . $productId,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            
            // Couleurs
            'product_color' => 'required|string|max:100',
            'family_color' => 'required|string|max:100',
            'group_code' => 'nullable|string|max:100',
            
            // Prix et remises
            'product_price' => 'required|numeric|min:0|max:999999.99',
            'product_discount' => 'nullable|numeric|min:0|max:100',
            'product_gst' => 'nullable|numeric|min:0|max:100',
            'discount_applied_on' => 'nullable|in:product,category,brand',
            
            // Stock et poids
            'stock' => 'required|integer|min:0|max:999999',
            'product_weight' => 'nullable|numeric|min:0|max:99999.99',
            
            // Fichiers
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'product_video' => 'nullable|mimes:mp4,avi,mov,wmv|max:10240', // 10MB max
            
            // Descriptions
            'description' => 'nullable|string|max:5000',
            'wash_care' => 'nullable|string|max:1000',
            'search_keywords' => 'nullable|string|max:500',
            
            // Détails du produit
            'fabric' => 'nullable|string|max:100',
            'pattern' => 'nullable|string|max:100',
            'sleeve' => 'nullable|string|max:100',
            'fit' => 'nullable|string|max:100',
            'occasion' => 'nullable|string|max:100',
            
            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            
            // Options
            'is_featured' => 'nullable|in:Yes,No',
            'status' => 'nullable|boolean',
            'sort' => 'nullable|integer|min:0'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            // Messages pour les champs requis
            'product_name.required' => 'Le nom du produit est obligatoire.',
            'product_code.required' => 'Le code du produit est obligatoire.',
            'product_code.unique' => 'Ce code produit existe déjà.',
            'category_id.required' => 'La catégorie est obligatoire.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'product_color.required' => 'La couleur du produit est obligatoire.',
            'family_color.required' => 'La famille de couleur est obligatoire.',
            'product_price.required' => 'Le prix du produit est obligatoire.',
            'stock.required' => 'Le stock est obligatoire.',
            
            // Messages pour les types
            'product_price.numeric' => 'Le prix doit être un nombre.',
            'product_discount.numeric' => 'La remise doit être un nombre.',
            'stock.integer' => 'Le stock doit être un nombre entier.',
            'product_weight.numeric' => 'Le poids doit être un nombre.',
            
            // Messages pour les limites
            'product_name.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',
            'product_code.max' => 'Le code produit ne peut pas dépasser 100 caractères.',
            'product_price.min' => 'Le prix ne peut pas être négatif.',
            'product_price.max' => 'Le prix ne peut pas dépasser 999999.99.',
            'product_discount.min' => 'La remise ne peut pas être négative.',
            'product_discount.max' => 'La remise ne peut pas dépasser 100%.',
            'stock.min' => 'Le stock ne peut pas être négatif.',
            'stock.max' => 'Le stock ne peut pas dépasser 999999.',
            'description.max' => 'La description ne peut pas dépasser 5000 caractères.',
            
            // Messages pour les fichiers
            'main_image.image' => 'Le fichier doit être une image.',
            'main_image.mimes' => 'L\'image doit être au format JPEG, PNG, JPG, GIF ou WebP.',
            'main_image.max' => 'L\'image ne peut pas dépasser 2MB.',
            'product_video.mimes' => 'La vidéo doit être au format MP4, AVI, MOV ou WMV.',
            'product_video.max' => 'La vidéo ne peut pas dépasser 10MB.',
            
            // Messages pour les énumérations
            'discount_applied_on.in' => 'Le type de remise doit être "product", "category" ou "brand".',
            'is_featured.in' => 'Le statut vedette doit être "Yes" ou "No".',
            
            // Messages pour les relations
            'brand_id.exists' => 'La marque sélectionnée n\'existe pas.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'product_name' => 'nom du produit',
            'product_code' => 'code du produit',
            'category_id' => 'catégorie',
            'brand_id' => 'marque',
            'product_color' => 'couleur du produit',
            'family_color' => 'famille de couleur',
            'product_price' => 'prix',
            'product_discount' => 'remise',
            'stock' => 'stock',
            'main_image' => 'image principale',
            'product_video' => 'vidéo du produit',
            'description' => 'description',
            'wash_care' => 'instructions de lavage',
            'meta_title' => 'titre meta',
            'meta_description' => 'description meta',
            'is_featured' => 'produit vedette',
            'status' => 'statut'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convertir les checkboxes en valeurs appropriées
        if ($this->has('is_featured') && $this->is_featured) {
            $this->merge(['is_featured' => 'Yes']);
        } else {
            $this->merge(['is_featured' => 'No']);
        }

        if ($this->has('status') && $this->status) {
            $this->merge(['status' => 1]);
        } else {
            $this->merge(['status' => 0]);
        }

        // Nettoyer les données
        if ($this->has('search_keywords')) {
            $this->merge([
                'search_keywords' => trim($this->search_keywords)
            ]);
        }

        // S'assurer que les valeurs numériques sont correctes
        if ($this->has('product_discount') && empty($this->product_discount)) {
            $this->merge(['product_discount' => 0]);
        }

        if ($this->has('product_gst') && empty($this->product_gst)) {
            $this->merge(['product_gst' => 0]);
        }

        if ($this->has('product_weight') && empty($this->product_weight)) {
            $this->merge(['product_weight' => 0]);
        }
    }
}
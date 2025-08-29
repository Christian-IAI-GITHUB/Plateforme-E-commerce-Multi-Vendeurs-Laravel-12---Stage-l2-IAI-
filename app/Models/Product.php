<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    public function category() {
        return $this->belongsTo('App\Models\Category', 'category_id')->with('parentcategory');
    }

    public function product_images(){
        return $this->hasMany(ProductsImage::class)->orderBy('sort', 'asc');
    }

    public function attributes(){
        return $this->hasMany('App\Models\ProductsAttribute');
    }

   
   
 

     use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'admin_id',
        'admin_role',
        'user_id',
        'product_name',
        'product_code',
        'product_color',
        'family_color',
        'group_code',
        'product_price',
        'product_discount',
        'product_discount_amount',
        'discount_applied_on',
        'product_gst',
        'final_price',
        'main_image',
        'product_weight',
        'product_video',
        'description',
        'wash_care',
        'search_keywords',
        'fabric',
        'pattern',
        'sleeve',
        'fit',
        'occasion',
        'stock',
        'sort',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_featured',
        'status'
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'product_discount' => 'decimal:2',
        'product_discount_amount' => 'decimal:2',
        'product_gst' => 'decimal:2',
        'final_price' => 'decimal:2',
        'product_weight' => 'decimal:2',
        'stock' => 'integer',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relations
     */
    
    /**
     * Relation avec la catégorie
     *//* 
    public function category()
    {
        return $this->belongsTo(Category::class);
    } */

    /**
     * Relation avec la marque
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Relation avec le vendeur/utilisateur
     */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec l'utilisateur (alias pour vendor)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scopes
     */
    
    /**
     * Scope pour les produits actifs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope pour les produits inactifs
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    /**
     * Scope pour les produits vedettes
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 'Yes');
    }

    /**
     * Scope pour les produits d'un vendeur spécifique
     */
    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('user_id', $vendorId);
    }

    /**
     * Scope pour les produits en stock
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope pour les produits en rupture
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    /**
     * Accessors & Mutators
     */
    
    /**
     * Accessor pour l'URL de l'image principale
     */
    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            return asset('front/images/products/' . $this->main_image);
        }
        return asset('front/images/no-image.jpg'); // Image par défaut
    }

    /**
     * Accessor pour le statut textuel
     */
    public function getStatusTextAttribute()
    {
        return $this->status ? 'Actif' : 'Inactif';
    }

    /**
     * Accessor pour le badge de statut
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status 
            ? '<span class="badge bg-success">Actif</span>' 
            : '<span class="badge bg-secondary">Inactif</span>';
    }

    /**
     * Accessor pour le prix formaté
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->product_price, 2) . ' €';
    }

    /**
     * Accessor pour le prix final formaté
     */
    public function getFormattedFinalPriceAttribute()
    {
        return number_format($this->final_price, 2) . ' €';
    }

    /**
     * Vérifier si le produit est en stock
     */
    public function isInStock()
    {
        return $this->stock > 0;
    }

    /**
     * Vérifier si le produit est actif
     */
    public function isActive()
    {
        return $this->status == 1;
    }

    /**
     * Vérifier si le produit est vedette
     */
    public function isFeatured()
    {
        return $this->is_featured == 'Yes';
    }

    /**
     * Vérifier si le produit a une remise
     */
    public function hasDiscount()
    {
        return $this->product_discount > 0;
    }

    /**
     * Calculer automatiquement le prix final lors de la sauvegarde
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            // Calculer automatiquement le montant de la remise et le prix final
            if ($product->product_discount > 0) {
                $product->product_discount_amount = ($product->product_price * $product->product_discount) / 100;
                $product->final_price = $product->product_price - $product->product_discount_amount;
            } else {
                $product->product_discount_amount = 0;
                $product->final_price = $product->product_price;
            }
        });
    }

/*     public function reviews()
{
    return $this->hasMany(Review::class);
} */

}

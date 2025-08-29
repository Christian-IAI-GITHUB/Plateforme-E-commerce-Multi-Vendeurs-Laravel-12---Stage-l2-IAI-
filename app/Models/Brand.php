<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'logo',
        'discount',
        'url',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'menu_status',
        'status'
    ];

    protected $casts = [
        'discount' => 'decimal:2',
        'menu_status' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Relations
     */
    
    /**
     * Relation avec les produits
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scopes
     */
    
    /**
     * Scope pour les marques actives
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope pour les marques dans le menu
     */
    public function scopeInMenu($query)
    {
        return $query->where('menu_status', 1);
    }

    /**
     * Accessors
     */
    
    /**
     * Accessor pour l'URL de l'image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('front/images/brands/' . $this->image);
        }
        return asset('front/images/brands/default.jpg');
    }

    /**
     * Accessor pour l'URL du logo
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('front/images/logos/' . $this->logo);
        }
        return asset('front/images/logos/default.jpg');
    }

    /**
     * Vérifier si la marque a une remise
     */
    public function hasDiscount()
    {
        return $this->discount > 0;
    }
}
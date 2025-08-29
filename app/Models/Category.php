<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    public function parentcategory() {
        return $this->hasOne(Category::class, 'id', 'parent_id')
                    ->select('id', 'name', 'url')  
                    ->where('status', 1)
                    ->orderBy('id', 'ASC');
    }

    public function subcategories(){
        return $this->hasMany(Category::class, 'parent_id')->where('status', 1);
    }


    public static function getCategories($type){
        $getCategories = Category::with(['subcategories.subcategories'])
            ->where('parent_id', NULL)
            ->where('status', 1);
        if ($type == "Front") {
            $getCategories = $getCategories->where('menu_status', 1);
        }
        return $getCategories->get()->toArray();
    }

    public static function categoryDetails($url)
{
    // Récupération de la catégorie et ses sous-catégories
    $category = self::with(['subcategories' => function($q){
        $q->with(['subcategories:id,parent_id,name']);
    }])
    ->where('url', $url)
    ->where('status', 1)
    ->first();

    if (!$category) return null;

    // Construction des IDs de catégorie
    $catIds = [$category->id];
    foreach ($category->subcategories as $subcat) {
        $catIds[] = $subcat->id;
        foreach ($subcat->subcategories as $subsubcat) {
            $catIds[] = $subsubcat->id;
        }
    }

    // Début des breadcrumbs (sans div vide)
    $breadcrumbs = '<div class="px-2 py-1 mb-1" style="background-color:#f9f9f9;">';
    $breadcrumbs .= '<nav aria-label="breadcrumb">';
    $breadcrumbs .= '<ol class="breadcrumb mb-0" style="background-color:#f9f9f9; --bs-breadcrumb-divider: \'>\';">';
    $breadcrumbs .= '<li class="breadcrumb-item"><a href="' . url('/') . '">Home</a></li>';

    if ($category->parent_id == 0) {
        // Catégorie de premier niveau
        $breadcrumbs .= '<li class="breadcrumb-item active" aria-current="page"><strong>' . $category->name . '</strong></li>';
    } else {
        // Catégorie enfant : on récupère la catégorie parente
        $parentCategory = self::select('id', 'url', 'name')
            ->where('id', $category->parent_id)
            ->first();

        if ($parentCategory) {
            $breadcrumbs .= '<li class="breadcrumb-item"><a href="' . url($parentCategory->url) . '" class="text-dark text-decoration-none">' . $parentCategory->name . '</a></li>';
        }

        // Catégorie actuelle
        $breadcrumbs .= '<li class="breadcrumb-item active" aria-current="page"><strong>' . $category->name . '</strong></li>';
    }

    $breadcrumbs .= '</ol>';
    $breadcrumbs .= '</nav>';
    $breadcrumbs .= '</div>';

    // Résultat final
    return [
        'catIds' => $catIds,
        'categoryDetails' => $category,
        'breadcrumbs' => $breadcrumbs
    ];
}


   use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'image',
        'size_chart',
        'discount',
        'description',
        'url',
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
     * Relation parent (catégorie parente)
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relation enfants (sous-catégories)
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Scopes
     */
    
    /**
     * Scope pour les catégories actives
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope pour les catégories dans le menu
     */
    public function scopeInMenu($query)
    {
        return $query->where('menu_status', 1);
    }

    /**
     * Scope pour les catégories principales (sans parent)
     */
    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope pour les sous-catégories
     */
    public function scopeSubCategories($query)
    {
        return $query->whereNotNull('parent_id');
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
            return asset('front/images/categories/' . $this->image);
        }
        return asset('front/images/categories/no-image.jpg');
    }

    /**
     * Vérifier si c'est une catégorie principale
     */
    public function isMainCategory()
    {
        return is_null($this->parent_id);
    }

    /**
     * Vérifier si la catégorie a des sous-catégories
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }
}


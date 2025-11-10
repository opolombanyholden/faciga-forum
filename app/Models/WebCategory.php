<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class WebCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'parent_id', 'sort_order',
        'is_active', 'icon', 'color', 'metadata', 'created_by', 'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Boot du modèle pour générer automatiquement le slug
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
        
        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relation avec la catégorie parente
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(WebCategory::class, 'parent_id');
    }

    /**
     * Relation avec les catégories enfants
     */
    public function children(): HasMany
    {
        return $this->hasMany(WebCategory::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Relation avec les catégories enfants actives
     */
    public function activeChildren(): HasMany
    {
        return $this->children()->where('is_active', true);
    }

    /**
     * Relation avec tous les descendants (récursive)
     */
    public function descendants(): HasMany
    {
        return $this->hasMany(WebCategory::class, 'parent_id')->with('descendants');
    }

    /**
     * Relation avec les contenus de cette catégorie
     */
    public function contents(): HasMany
    {
        return $this->hasMany(WebContent::class, 'category_id')->orderBy('sort_order');
    }

    /**
     * Relation avec les contenus actifs
     */
    public function activeContents(): HasMany
    {
        return $this->contents()->where('is_active', true);
    }

    /**
     * Relation avec le créateur
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    /**
     * Relation avec le dernier modificateur
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'updated_by');
    }

    /**
     * Scope pour les catégories actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les catégories racines (sans parent)
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope pour les catégories avec un parent spécifique
     */
    public function scopeChildrenOf($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    /**
     * Vérifier si la catégorie est une racine
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Vérifier si la catégorie a des enfants
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Récupérer le chemin complet de la catégorie
     */
    public function getFullPath(): string
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }

    /**
     * Récupérer tous les ancêtres
     */
    public function getAncestors(): array
    {
        $ancestors = [];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($ancestors, $parent);
            $parent = $parent->parent;
        }
        
        return $ancestors;
    }

    /**
     * Récupérer la profondeur dans la hiérarchie
     */
    public function getDepth(): int
    {
        return count($this->getAncestors());
    }

    /**
     * Vérifier si cette catégorie peut être parente d'une autre
     * (évite les références circulaires)
     */
    public function canBeParentOf(WebCategory $category): bool
    {
        // Une catégorie ne peut pas être parente d'elle-même
        if ($this->id === $category->id) {
            return false;
        }
        
        // Une catégorie ne peut pas être parente de ses ancêtres
        $ancestors = $category->getAncestors();
        foreach ($ancestors as $ancestor) {
            if ($ancestor->id === $this->id) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Récupérer le nombre total de contenus (incluant les sous-catégories)
     */
    public function getTotalContentsCount(): int
    {
        $count = $this->contents()->count();
        
        foreach ($this->children as $child) {
            $count += $child->getTotalContentsCount();
        }
        
        return $count;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebContent extends Model
{
    protected $fillable = [
        'key', 'title', 'content', 'content_type', 'category_id',
        'is_active', 'meta_description', 'metadata', 'sort_order',
        'created_by', 'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    // Constantes pour les types de contenu
    public const TYPE_TEXT = 'text';
    public const TYPE_HTML = 'html';
    public const TYPE_IMAGE = 'image';
    public const TYPE_VIDEO = 'video';
    public const TYPE_FILE = 'file';

    /**
     * Relation avec la catégorie
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(WebCategory::class);
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
     * Scope pour le contenu actif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour filtrer par type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('content_type', $type);
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope pour le contenu d'une catégorie et ses sous-catégories
     */
    public function scopeByCategoryAndChildren($query, $categoryId)
    {
        $category = WebCategory::find($categoryId);
        if (!$category) {
            return $query->where('category_id', $categoryId);
        }

        $categoryIds = [$categoryId];
        $this->addChildrenIds($category, $categoryIds);

        return $query->whereIn('category_id', $categoryIds);
    }

    /**
     * Méthode helper pour récupérer récursivement les IDs des catégories enfants
     */
    private function addChildrenIds(WebCategory $category, array &$categoryIds): void
    {
        foreach ($category->children as $child) {
            $categoryIds[] = $child->id;
            $this->addChildrenIds($child, $categoryIds);
        }
    }

    /**
     * Récupérer le contenu par clé
     */
    public static function getByKey(string $key, $default = null)
    {
        $content = static::where('key', $key)->where('is_active', true)->first();
        return $content ? $content->content : $default;
    }

    /**
     * Récupérer le contenu avec sa catégorie par clé
     */
    public static function getWithCategoryByKey(string $key)
    {
        return static::with('category')
                    ->where('key', $key)
                    ->where('is_active', true)
                    ->first();
    }

    /**
     * Vérifier si le contenu est une image
     */
    public function isImage(): bool
    {
        return $this->content_type === self::TYPE_IMAGE;
    }

    /**
     * Vérifier si le contenu est une vidéo
     */
    public function isVideo(): bool
    {
        return $this->content_type === self::TYPE_VIDEO;
    }

    /**
     * Vérifier si le contenu est du HTML
     */
    public function isHtml(): bool
    {
        return $this->content_type === self::TYPE_HTML;
    }

    /**
     * Récupérer le chemin complet incluant la catégorie
     */
    public function getFullPath(): string
    {
        $categoryPath = $this->category ? $this->category->getFullPath() : 'Sans catégorie';
        return $categoryPath . ' > ' . $this->title;
    }

    /**
     * Récupérer le contenu formaté selon son type
     */
    public function getFormattedContent(): string
    {
        switch ($this->content_type) {
            case self::TYPE_HTML:
                return $this->content;
            case self::TYPE_TEXT:
                return nl2br(e($this->content));
            case self::TYPE_IMAGE:
                $alt = $this->metadata['alt'] ?? $this->title;
                return "<img src=\"{$this->content}\" alt=\"{$alt}\" class=\"img-fluid\">";
            case self::TYPE_VIDEO:
                return "<video controls class=\"w-100\"><source src=\"{$this->content}\"></video>";
            default:
                return $this->content;
        }
    }
}
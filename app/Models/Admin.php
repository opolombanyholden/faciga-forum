<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'phone',
        'role', 
        'status', 
        'last_login_at',
        'permissions'
    ];

    protected $hidden = [
        'password', 
        'remember_token'
    ];

    protected $casts = [
        'permissions' => 'array',
        'last_login_at' => 'datetime',
    ];

    // Constantes pour les rôles
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_MODERATOR = 'moderator';
    public const ROLE_ANALYST = 'analyst';
    public const ROLE_WEBMASTER = 'webmaster';

    // Constantes pour les statuts
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_SUSPENDED = 'suspended';

    /**
     * Vérifier si l'admin a un rôle spécifique
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Vérifier si l'admin est super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(self::ROLE_SUPER_ADMIN);
    }

    /**
     * Vérifier si l'admin est modérateur
     */
    public function isModerator(): bool
    {
        return $this->hasRole(self::ROLE_MODERATOR);
    }

    /**
     * Vérifier si l'admin est analyste
     */
    public function isAnalyst(): bool
    {
        return $this->hasRole(self::ROLE_ANALYST);
    }

    /**
     * Vérifier si l'admin est webmaster
     */
    public function isWebmaster(): bool
    {
        return $this->hasRole(self::ROLE_WEBMASTER);
    }

    /**
     * Vérifier si l'admin est actif
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Vérifier si l'admin peut gérer les entreprises (valider/rejeter)
     */
    public function canManageCompanies(): bool
    {
        return $this->isSuperAdmin() || $this->isModerator();
    }

    /**
     * Vérifier si l'admin peut créer/gérer des participants
     */
    public function canManageParticipants(): bool
    {
        return $this->isSuperAdmin() || $this->isModerator();
    }

    /**
     * Vérifier si l'admin peut voir les données
     */
    public function canViewData(): bool
    {
        return in_array($this->role, [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_MODERATOR,
            self::ROLE_ANALYST
        ]);
    }

    /**
     * Vérifier si l'admin peut créer/gérer les rubriques
     */
    public function canManageCategories(): bool
    {
        return $this->isSuperAdmin() || $this->isModerator();
    }

    /**
     * Vérifier si l'admin peut gérer le contenu web
     */
    public function canManageWebContent(): bool
    {
        return $this->isSuperAdmin() || $this->isWebmaster();
    }

    /**
     * Vérifier si l'admin peut gérer les autres admins
     */
    public function canManageAdmins(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Relations
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(AdminActivityLog::class);
    }

    public function createdWebCategories(): HasMany
    {
        return $this->hasMany(WebCategory::class, 'created_by');
    }

    public function createdWebContents(): HasMany
    {
        return $this->hasMany(WebContent::class, 'created_by');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(AdminSession::class);
    }

    /**
     * Enregistrer une activité
     */
    public function logActivity(
        string $action, 
        $model = null, 
        array $oldValues = [], 
        array $newValues = [],
        ?string $description = null
    ): void {
        $this->activityLogs()->create([
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => $description,
        ]);
    }

    /**
     * Mettre à jour le timestamp de dernière connexion
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Scope pour filtrer par rôle
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope pour les admins actifs
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Récupérer un label lisible pour le rôle
     */
    public function getRoleLabel(): string
    {
        return match($this->role) {
            self::ROLE_SUPER_ADMIN => 'Super Administrateur',
            self::ROLE_MODERATOR => 'Modérateur',
            self::ROLE_ANALYST => 'Analyste',
            self::ROLE_WEBMASTER => 'Webmaster',
            default => 'Inconnu',
        };
    }

    /**
     * Récupérer un label lisible pour le statut
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'Actif',
            self::STATUS_INACTIVE => 'Inactif',
            self::STATUS_SUSPENDED => 'Suspendu',
            default => 'Inconnu',
        };
    }
}
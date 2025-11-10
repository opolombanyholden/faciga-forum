<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * Le guard utilisé pour l'authentification
     */
    protected $guard = 'admin';

    /**
     * La table associée au modèle
     */
    protected $table = 'admins';

    /**
     * Les attributs mass assignable
     */
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

    /**
     * Les attributs qui doivent être cachés
     */
    protected $hidden = [
        'password', 
        'remember_token'
    ];

    /**
     * Les attributs qui doivent être castés
     */
    protected $casts = [
        'permissions' => 'array',
        'last_login_at' => 'datetime',
        'email_verified_at' => 'datetime',
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
     * Vérifier si l'admin peut gérer le contenu web
     */
    public function canManageContent(): bool
    {
        return $this->isSuperAdmin() || $this->isWebmaster();
    }

    /**
     * Obtenir le nom du rôle en français
     */
    public function getRoleNameAttribute(): string
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
     * Obtenir le nom du statut en français
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ACTIVE => 'Actif',
            self::STATUS_INACTIVE => 'Inactif',
            self::STATUS_SUSPENDED => 'Suspendu',
            default => 'Inconnu',
        };
    }

    /**
     * Scope pour les admins actifs
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope pour un rôle spécifique
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}
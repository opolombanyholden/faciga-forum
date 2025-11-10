<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminSession extends Model
{
    protected $fillable = [
        'admin_id',
        'session_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'location',
        'login_at',
        'logout_at',
        'last_activity',
        'is_active',
        'forced_logout',
        'logout_reason'
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'last_activity' => 'datetime',
        'is_active' => 'boolean',
        'forced_logout' => 'boolean',
    ];

    /**
     * Relation avec l'admin
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Scope pour les sessions actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les sessions d'un admin
     */
    public function scopeByAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * Scope pour les sessions aujourd'hui
     */
    public function scopeToday($query)
    {
        return $query->whereDate('login_at', today());
    }

    /**
     * Terminer la session
     */
    public function terminate(?string $reason = null): void
    {
        $this->update([
            'logout_at' => now(),
            'is_active' => false,
            'logout_reason' => $reason,
        ]);
    }

    /**
     * Forcer la déconnexion
     */
    public function forceLogout(string $reason): void
    {
        $this->update([
            'logout_at' => now(),
            'is_active' => false,
            'forced_logout' => true,
            'logout_reason' => $reason,
        ]);
    }

    /**
     * Mettre à jour l'activité
     */
    public function updateActivity(): void
    {
        $this->update(['last_activity' => now()]);
    }
}
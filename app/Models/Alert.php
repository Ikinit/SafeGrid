<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alert extends Model
{
    protected $fillable = [
        'title',
        'message',
        'type',                     // 'admin' | 'expiry'
        'checklist_item_id',        // personal go-bag item
        'checklist_item_family_id', // family go-bag item
        'user_id',                  // null = broadcast; set = scoped to one user (expiry)
    ];

    /**
     * The admin or system user who created this alert.
     * Only relevant for admin-posted alerts (user_id on the alert = the target user
     * for expiry alerts, NOT the author — author tracking can be added later if needed).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The personal go-bag item that triggered this alert (expiry alerts only).
     */
    public function checklistItem(): BelongsTo
    {
        return $this->belongsTo(\App\Models\GoBagItems::class, 'checklist_item_id');
    }

    /**
     * The family go-bag item that triggered this alert (expiry alerts only).
     */
    public function checklistItemFamily(): BelongsTo
    {
        return $this->belongsTo(\App\Models\GoBagItemsFamily::class, 'checklist_item_family_id');
    }

    /**
     * Read receipts.
     */
    public function reads(): HasMany
    {
        return $this->hasMany(AlertRead::class);
    }

    /**
     * Convenience: has the given user already read this alert?
     */
    public function isReadBy(User $user): bool
    {
        return $this->reads()->where('user_id', $user->id)->exists();
    }

    // ── Scopes ──────────────────────────────────────────────

    /**
     * Alerts visible to a given user:
     *   - broadcast admin alerts (user_id IS NULL, type = admin)
     *   - expiry alerts addressed to that specific user
     */
    public function scopeVisibleTo($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where(function ($q2) {
                $q2->where('type', 'admin')->whereNull('user_id');
            })->orWhere(function ($q2) use ($user) {
                $q2->where('type', 'expiry')->where('user_id', $user->id);
            });
        });
    }
}
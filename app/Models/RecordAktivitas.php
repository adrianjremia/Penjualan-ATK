<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecordAktivitas extends Model
{
    protected $table = 'record_aktivitas';

    protected $fillable = [
        'id_user',
        'action',
        'description',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relationship: belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Scope: filter by user
     */
    public function scopeByUser($query, $userId)
    {
        // Special case for "pemilik" - filter by role 1
        if ($userId === 'pemilik') {
            return $query->whereHas('user', function ($q) {
                $q->where('role', 1);
            });
        }
        
        return $query->where('id_user', $userId);
    }

    /**
     * Scope: filter by action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: filter by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope: search in description
     */
    public function scopeSearchDescription($query, $search)
    {
        return $query->where('description', 'like', "%{$search}%");
    }

    /**
     * Get human-readable action label
     */
    public function getActionLabelAttribute()
    {
        $labels = [
            'create_transaksi' => 'Membuat Transaksi',
            'edit_transaksi' => 'Edit Transaksi',
            'delete_transaksi' => 'Hapus Transaksi',
            'create_barang' => 'Membuat Barang',
            'edit_barang' => 'Edit Barang',
            'delete_barang' => 'Hapus Barang',
        ];

        return $labels[$this->action] ?? ucfirst($this->action);
    }
}

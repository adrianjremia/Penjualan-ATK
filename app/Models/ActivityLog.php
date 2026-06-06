<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';
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

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Helper method untuk display action label
    public function getActionLabel()
    {
        $labels = [
            'create_transaksi' => 'Buat Transaksi',
            'edit_transaksi' => 'Edit Transaksi',
            'delete_transaksi' => 'Hapus Transaksi',
            'create_barang' => 'Buat Barang',
            'edit_barang' => 'Edit Barang',
            'delete_barang' => 'Hapus Barang',
        ];

        return $labels[$this->action] ?? $this->action;
    }

    // Scope untuk filter by user
    public function scopeByUser($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    // Scope untuk filter by action
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    // Scope untuk filter by date range
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Scope untuk search description
    public function scopeSearchDescription($query, $search)
    {
        return $query->where('description', 'like', "%{$search}%");
    }
}

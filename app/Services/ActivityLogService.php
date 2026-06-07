<?php

namespace App\Services;

use App\Models\RecordAktivitas;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log generic activity
     */
    public static function log(
        $userId,
        $action,
        $description = null,
        $modelType = null,
        $modelId = null,
        $oldValues = null,
        $newValues = null
    ) {
        return RecordAktivitas::create([
            'id_user' => $userId,
            'action' => $action,
            'description' => $description,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent')
        ]);
    }

    /**
     * Log create action
     */
    public static function logCreate($userId, $modelType, $modelId, $values)
    {
        $description = "Buat transaksi";
        
        return self::log(
            $userId,
            "create_{$modelType}",
            $description,
            $modelType,
            $modelId,
            null,
            $values
        );
    }

    /**
     * Log update action
     */
    public static function logUpdate($userId, $modelType, $modelId, $oldValues, $newValues)
    {
        $description = "Edit transaksi";
        
        // Generate simple change summary
        if (!empty($newValues) && is_array($newValues)) {
            $changedItems = 0;
            $changedFields = [];
            
            // Count how many items changed
            foreach ($newValues as $index => $newVal) {
                if (isset($oldValues[$index])) {
                    $oldVal = $oldValues[$index];
                    // Check if any field in the item changed
                    if ($oldVal !== $newVal) {
                        $changedItems++;
                        // Extract simple field names that changed
                        if (is_array($newVal) && isset($newVal['barang'])) {
                            $barangName = $newVal['barang'];
                            if (isset($newVal['jumlah']) && isset($oldVal['jumlah']) && 
                                $newVal['jumlah'] != $oldVal['jumlah']) {
                                $changedFields[] = "jumlah {$barangName}";
                            }
                        }
                    }
                }
            }
            
            // Create simple description
            if ($changedItems > 0) {
                if (count($changedFields) > 0 && count($changedFields) <= 2) {
                    $description = "Ubah " . implode(", ", $changedFields);
                } else {
                    $description = "Ubah $changedItems item";
                }
            }
        }

        return self::log(
            $userId,
            "edit_{$modelType}",
            $description,
            $modelType,
            $modelId,
            $oldValues,
            $newValues
        );
    }

    /**
     * Log delete action
     */
    public static function logDelete($userId, $modelType, $modelId, $values = null)
    {
        $description = "Hapus transaksi";
        
        return self::log(
            $userId,
            "delete_{$modelType}",
            $description,
            $modelType,
            $modelId,
            $values,
            null
        );
    }
}

<?php

namespace App\Services;

use App\Models\ActivityLog;
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
        return ActivityLog::create([
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
        return self::log(
            $userId,
            "create_{$modelType}",
            "Membuat {$modelType} baru dengan ID {$modelId}",
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
        $description = "Memperbarui {$modelType} ID {$modelId}";
        if (!empty($oldValues) && !empty($newValues)) {
            $changes = [];
            foreach ($newValues as $key => $newVal) {
                if (isset($oldValues[$key]) && $oldValues[$key] !== $newVal) {
                    $changes[] = "{$key}: {$oldValues[$key]} → {$newVal}";
                }
            }
            if (!empty($changes)) {
                $description .= " (" . implode(", ", $changes) . ")";
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
        return self::log(
            $userId,
            "delete_{$modelType}",
            "Menghapus {$modelType} ID {$modelId}",
            $modelType,
            $modelId,
            $values,
            null
        );
    }
}

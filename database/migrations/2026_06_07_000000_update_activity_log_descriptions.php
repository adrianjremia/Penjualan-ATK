<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing activity logs with simplified descriptions
        DB::table('activity_log')->where('action', 'like', 'create_%')->update([
            'description' => DB::raw("'Buat transaksi'")
        ]);

        DB::table('activity_log')->where('action', 'like', 'delete_%')->update([
            'description' => DB::raw("'Hapus transaksi'")
        ]);

        // For edit actions, we need to simplify the long descriptions
        $editLogs = DB::table('activity_log')
            ->where('action', 'like', 'edit_%')
            ->get();

        foreach ($editLogs as $log) {
            $description = 'Ubah item';
            
            // Try to parse new_values to count changes
            if ($log->new_values) {
                try {
                    $newValues = json_decode($log->new_values, true);
                    if (is_array($newValues)) {
                        $changedItems = count($newValues);
                        if ($changedItems == 1) {
                            $description = 'Ubah 1 item';
                        } else {
                            $description = "Ubah $changedItems item";
                        }
                    }
                } catch (\Exception $e) {
                    // Keep default description
                }
            }
            
            DB::table('activity_log')
                ->where('id_activity_log', $log->id_activity_log)
                ->update(['description' => $description]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed as this is just updating descriptions
    }
};

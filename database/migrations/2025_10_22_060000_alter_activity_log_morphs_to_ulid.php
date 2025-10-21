<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $connection = config('activitylog.database_connection');
        $table = config('activitylog.table_name');

        $conn = DB::connection($connection);
        $qualified = $conn->getTablePrefix() . $table;

        // Convert morph id columns to CHAR(26) to store ULIDs
        $conn->statement("ALTER TABLE `{$qualified}` MODIFY `subject_id` CHAR(26) NULL");
        $conn->statement("ALTER TABLE `{$qualified}` MODIFY `causer_id` CHAR(26) NULL");
    }

    public function down(): void
    {
        $connection = config('activitylog.database_connection');
        $table = config('activitylog.table_name');

        $conn = DB::connection($connection);
        $qualified = $conn->getTablePrefix() . $table;

        // Revert back to BIGINT UNSIGNED (may fail if values are not numeric)
        $conn->statement("ALTER TABLE `{$qualified}` MODIFY `subject_id` BIGINT UNSIGNED NULL");
        $conn->statement("ALTER TABLE `{$qualified}` MODIFY `causer_id` BIGINT UNSIGNED NULL");
    }
};


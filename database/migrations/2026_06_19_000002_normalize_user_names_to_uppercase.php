<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Normalisasi semua nama user ke UPPERCASE.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereRaw('BINARY name != BINARY UPPER(name)')
            ->update(['name' => DB::raw('UPPER(name)')]);
    }

    public function down(): void
    {
        // Tidak bisa di-rollback secara sempurna
    }
};

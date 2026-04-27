<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('anggota', function (Blueprint $table) {
            $table->string('npm')->nullable()->change();
            $table->string('prodi')->nullable()->change();
            $table->string('angkatan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('anggota')->whereNull('npm')->update([
            'npm' => DB::raw("CONCAT('TEMP-', id)"),
        ]);

        DB::table('anggota')->whereNull('prodi')->update([
            'prodi' => '-',
        ]);

        DB::table('anggota')->whereNull('angkatan')->update([
            'angkatan' => '-',
        ]);

        Schema::table('anggota', function (Blueprint $table) {
            $table->string('npm')->nullable(false)->change();
            $table->string('prodi')->nullable(false)->change();
            $table->string('angkatan')->nullable(false)->change();
        });
    }
};

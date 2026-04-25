<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('divisi')->nullable()->after('role');
            $table->string('role_detail')->nullable()->after('divisi');
        });

        Schema::table('kode_absensi', function (Blueprint $table) {
            $table->unsignedInteger('expired_minutes')->default(15)->after('expired_at');
        });

        Schema::create('kegiatan_anggota', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['kegiatan_id', 'user_id']);
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->text('description');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('kegiatan_anggota');

        Schema::table('kode_absensi', function (Blueprint $table) {
            $table->dropColumn('expired_minutes');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['divisi', 'role_detail']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Merge kegiatan duplikat berdasarkan nama kegiatan (huruf besar).
     * Pertahankan record tertua (id terkecil), pindahkan relasi, hapus sisanya.
     */
    public function up(): void
    {
        // Normalisasi semua nama kegiatan ke UPPERCASE terlebih dahulu
        // Pakai BINARY karena collation MySQL default case-insensitive
        DB::table('kegiatan')
            ->whereRaw('BINARY nama_kegiatan != BINARY UPPER(nama_kegiatan)')
            ->update(['nama_kegiatan' => DB::raw('UPPER(nama_kegiatan)')]);

        // Cari nama_kegiatan yang punya lebih dari 1 record (duplikat)
        $duplicates = DB::table('kegiatan')
            ->select('nama_kegiatan')
            ->groupBy('nama_kegiatan')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('nama_kegiatan');

        foreach ($duplicates as $nama) {
            // Ambil semua id dengan nama yang sama, urutkan dari yang terkecil
            $ids = DB::table('kegiatan')
                ->where('nama_kegiatan', $nama)
                ->orderBy('id')
                ->pluck('id');

            // Pertahankan yang pertama (id terkecil)
            $keepId = $ids->first();
            $removeIds = $ids->slice(1)->values();

            if ($removeIds->isEmpty()) {
                continue;
            }

            // Pindahkan absensi
            DB::table('absensi')
                ->whereIn('kegiatan_id', $removeIds)
                ->update(['kegiatan_id' => $keepId]);

            // Pindahkan kode_absensi
            DB::table('kode_absensi')
                ->whereIn('kegiatan_id', $removeIds)
                ->update(['kegiatan_id' => $keepId]);

            // Pindahkan kegiatan_anggota (pivot), hindari duplicate
            foreach ($removeIds as $removeId) {
                $existing = DB::table('kegiatan_anggota')
                    ->where('kegiatan_id', $keepId)
                    ->pluck('user_id');

                $assignments = DB::table('kegiatan_anggota')
                    ->where('kegiatan_id', $removeId)
                    ->get();

                foreach ($assignments as $assignment) {
                    if (!$existing->contains($assignment->user_id)) {
                        DB::table('kegiatan_anggota')->insert([
                            'kegiatan_id' => $keepId,
                            'user_id' => $assignment->user_id,
                            'created_at' => $assignment->created_at,
                            'updated_at' => $assignment->updated_at,
                        ]);
                    }
                }
            }

            // Hapus relasi pivot yang tersisa
            DB::table('kegiatan_anggota')
                ->whereIn('kegiatan_id', $removeIds)
                ->delete();

            // Hapus kegiatan duplikat
            DB::table('kegiatan')
                ->whereIn('id', $removeIds)
                ->delete();
        }
    }

    public function down(): void
    {
        // Tidak bisa di-rollback secara sempurna karena data duplikat sudah dihapus
    }
};

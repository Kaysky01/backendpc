<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\KodeAbsensi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superAdmin = User::query()->updateOrCreate([
            'email' => 'superadmin@polinela.test',
        ], [
            'name' => 'Super Admin PCAS',
            'password' => 'password',
            'role' => User::ROLE_ADMIN,
            'is_super_admin' => true,
            'email_verified_at' => now(),
        ]);

        User::query()->updateOrCreate([
            'email' => 'admin@polinela.test',
        ], [
            'name' => 'Admin Operasional',
            'password' => 'password',
            'role' => User::ROLE_ADMIN,
            'is_super_admin' => false,
            'email_verified_at' => now(),
        ]);

        $anggotaUsers = collect([
            ['name' => 'Alya Pratama', 'email' => 'alya@polinela.test', 'npm' => '2315100101', 'prodi' => 'Teknologi Informasi', 'angkatan' => '2023'],
            ['name' => 'Bintang Saputra', 'email' => 'bintang@polinela.test', 'npm' => '2315100102', 'prodi' => 'Teknologi Informasi', 'angkatan' => '2023'],
            ['name' => 'Citra Ramadhani', 'email' => 'citra@polinela.test', 'npm' => '2315100103', 'prodi' => 'Manajemen Informatika', 'angkatan' => '2022'],
            ['name' => 'Dimas Prakoso', 'email' => 'dimas@polinela.test', 'npm' => '2315100104', 'prodi' => 'Manajemen Informatika', 'angkatan' => '2022'],
        ])->map(function (array $item) {
            $user = User::query()->updateOrCreate([
                'email' => $item['email'],
            ], [
                'name' => $item['name'],
                'password' => 'password',
                'role' => User::ROLE_ANGGOTA,
                'is_super_admin' => false,
                'email_verified_at' => now(),
            ]);

            $user->anggota()->updateOrCreate([], [
                'npm' => $item['npm'],
                'prodi' => $item['prodi'],
                'angkatan' => $item['angkatan'],
            ]);

            return $user;
        });

        $kegiatan = collect([
            [
                'nama_kegiatan' => 'Workshop Branding Kreatif',
                'tanggal' => now()->subMonths(2)->startOfMonth()->addDays(5)->toDateString(),
                'lokasi' => 'Gedung Kreatif Polinela',
                'deskripsi' => 'Pelatihan identitas visual dan strategi branding untuk anggota baru.',
            ],
            [
                'nama_kegiatan' => 'Rapat Koordinasi Divisi',
                'tanggal' => now()->subMonth()->startOfMonth()->addDays(10)->toDateString(),
                'lokasi' => 'Ruang Multimedia',
                'deskripsi' => 'Sinkronisasi program kerja dan evaluasi kegiatan bulanan.',
            ],
            [
                'nama_kegiatan' => 'Sesi Foto Produk UMKM',
                'tanggal' => now()->subDays(12)->toDateString(),
                'lokasi' => 'Studio Foto Kampus',
                'deskripsi' => 'Produksi konten visual untuk kolaborasi UMKM binaan.',
            ],
            [
                'nama_kegiatan' => 'Kelas Editing Video',
                'tanggal' => now()->addDays(5)->toDateString(),
                'lokasi' => 'Laboratorium Komputer 2',
                'deskripsi' => 'Pelatihan editing video promosi dan short-form content.',
            ],
            [
                'nama_kegiatan' => 'Briefing Event Kampus',
                'tanggal' => now()->addDays(15)->toDateString(),
                'lokasi' => 'Aula Utama',
                'deskripsi' => 'Persiapan tim kreatif untuk event kampus mendatang.',
            ],
        ])->map(fn (array $item) => Kegiatan::query()->updateOrCreate([
            'nama_kegiatan' => $item['nama_kegiatan'],
        ], $item));

        $firstKegiatan = $kegiatan[0];
        $secondKegiatan = $kegiatan[1];
        $thirdKegiatan = $kegiatan[2];
        $futureKegiatan = $kegiatan[3];

        foreach ($anggotaUsers as $index => $user) {
            $records = [
                [
                    'kegiatan_id' => $firstKegiatan->id,
                    'status' => $index === 3 ? Absensi::STATUS_IZIN : Absensi::STATUS_HADIR,
                    'waktu_absen' => now()->subMonths(2)->startOfMonth()->addDays(5)->setTime(8 + $index, 15),
                ],
                [
                    'kegiatan_id' => $secondKegiatan->id,
                    'status' => $index === 2 ? Absensi::STATUS_ALFA : Absensi::STATUS_HADIR,
                    'waktu_absen' => now()->subMonth()->startOfMonth()->addDays(10)->setTime(9 + $index, 5),
                ],
            ];

            if ($index < 2) {
                $records[] = [
                    'kegiatan_id' => $thirdKegiatan->id,
                    'status' => Absensi::STATUS_HADIR,
                    'waktu_absen' => now()->subDays(12)->setTime(14, 10 + $index),
                ];
            }

            foreach ($records as $record) {
                Absensi::query()->updateOrCreate([
                    'user_id' => $user->id,
                    'kegiatan_id' => $record['kegiatan_id'],
                ], [
                    'status' => $record['status'],
                    'waktu_absen' => $record['waktu_absen'],
                ]);
            }
        }

        KodeAbsensi::query()->updateOrCreate([
            'kode' => 'PCAS01',
        ], [
            'kegiatan_id' => $futureKegiatan->id,
            'expired_at' => now()->addMinutes(15),
            'is_active' => true,
        ]);

        KodeAbsensi::query()->updateOrCreate([
            'kode' => 'PCAS99',
        ], [
            'kegiatan_id' => $thirdKegiatan->id,
            'expired_at' => now()->subMinutes(30),
            'is_active' => false,
        ]);
    }
}

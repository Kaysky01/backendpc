<?php

namespace App\Imports;

use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToCollection, WithHeadingRow
{
    private const DEFAULT_PASSWORD = 'password123';

    public int $imported = 0;

    public int $skipped = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $namaKegiatan = trim((string) ($row['nama_kegiatan'] ?? ''));
            $tanggal = trim((string) ($row['tanggal'] ?? ''));
            $lokasi = trim((string) ($row['lokasi'] ?? ''));
            $namaUser = trim((string) ($row['nama_user'] ?? ''));
            $email = strtolower(trim((string) ($row['email'] ?? '')));
            $status = strtolower(trim((string) ($row['status'] ?? '')));
            $waktuAbsen = trim((string) ($row['waktu_absen'] ?? ''));

            if (
                $namaKegiatan === ''
                || $tanggal === ''
                || $lokasi === ''
                || $namaUser === ''
                || $email === ''
                || $waktuAbsen === ''
                || ! in_array($status, Absensi::statusOptions(), true)
            ) {
                $this->skipped++;

                continue;
            }

            try {
                $tanggalKegiatan = Carbon::parse($tanggal)->toDateString();
                $waktuAbsensi = Carbon::parse($waktuAbsen);
            } catch (\Throwable) {
                $this->skipped++;

                continue;
            }

            $kegiatan = Kegiatan::query()->firstOrCreate(
                [
                    'nama_kegiatan' => $namaKegiatan,
                    'tanggal' => $tanggalKegiatan,
                    'lokasi' => $lokasi,
                ],
                [
                    'deskripsi' => 'Data kegiatan hasil import absensi.',
                ],
            );

            $user = User::query()->firstOrCreate(
                ['email' => $email],
                [
                    'name' => $namaUser,
                    'password' => self::DEFAULT_PASSWORD,
                    'role' => User::ROLE_ANGGOTA,
                ],
            );

            $kegiatan->assignedUsers()->syncWithoutDetaching([$user->id]);

            $exists = Absensi::query()
                ->where('user_id', $user->id)
                ->where('kegiatan_id', $kegiatan->id)
                ->exists();

            if ($exists) {
                $this->skipped++;

                continue;
            }

            Absensi::query()->create([
                'user_id' => $user->id,
                'kegiatan_id' => $kegiatan->id,
                'status' => $status,
                'waktu_absen' => $waktuAbsensi,
            ]);

            $this->imported++;
        }
    }
}

<?php

namespace App\Services;

use App\Models\Kegiatan;
use App\Models\KodeAbsensi;

class AttendanceCodeService
{
    private const CODE_LENGTH = 6;

    private const CODE_ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    public function generate(Kegiatan $kegiatan, int $expiredMinutes = 15): KodeAbsensi
    {
        KodeAbsensi::query()
            ->where('kegiatan_id', $kegiatan->id)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        do {
            $kode = $this->generateFixedLengthCode();
        } while (KodeAbsensi::query()->where('kode', $kode)->exists());

        return KodeAbsensi::query()->create([
            'kegiatan_id' => $kegiatan->id,
            'kode' => $kode,
            'expired_at' => now()->addMinutes($expiredMinutes),
            'expired_minutes' => $expiredMinutes,
            'is_active' => true,
        ]);
    }

    private function generateFixedLengthCode(): string
    {
        $alphabet = self::CODE_ALPHABET;
        $alphabetLength = strlen($alphabet) - 1;
        $code = '';

        for ($index = 0; $index < self::CODE_LENGTH; $index++) {
            $code .= $alphabet[random_int(0, $alphabetLength)];
        }

        return $code;
    }
}

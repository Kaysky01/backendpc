<?php

namespace App\Services;

use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class AttendanceStatusService
{
    public const STATUS_BELUM_ABSEN = 'belum_absen';

    public function determineStatus(User $user, Kegiatan $kegiatan, ?Absensi $absensi = null): string
    {
        $absensi ??= $this->resolveAttendance($user, $kegiatan);

        if ($absensi) {
            return $absensi->status;
        }

        if (! $this->isAssigned($user, $kegiatan)) {
            return Absensi::STATUS_TIDAK_DITUGASKAN;
        }

        return $kegiatan->tanggal->startOfDay()->lte(now()->startOfDay())
            ? Absensi::STATUS_ALFA
            : self::STATUS_BELUM_ABSEN;
    }

    public function isAssigned(User $user, Kegiatan $kegiatan): bool
    {
        if ($kegiatan->relationLoaded('assignedUsers')) {
            /** @var EloquentCollection<int, User> $assignedUsers */
            $assignedUsers = $kegiatan->assignedUsers;

            return $assignedUsers->contains('id', $user->id);
        }

        return $kegiatan->assignedUsers()
            ->where('users.id', $user->id)
            ->exists();
    }

    public function canAttend(User $user, Kegiatan $kegiatan): bool
    {
        return $this->isAssigned($user, $kegiatan);
    }

    private function resolveAttendance(User $user, Kegiatan $kegiatan): ?Absensi
    {
        if ($kegiatan->relationLoaded('absensis')) {
            /** @var EloquentCollection<int, Absensi> $absensis */
            $absensis = $kegiatan->absensis;

            return $absensis->firstWhere('user_id', $user->id);
        }

        return $kegiatan->absensis()
            ->where('user_id', $user->id)
            ->first();
    }
}

<?php

namespace Tests\Feature\Attendance;

use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\KodeAbsensi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_anggota_can_submit_a_valid_attendance_code(): void
    {
        $user = User::factory()->anggota()->create();
        $user->anggota()->create([
            'npm' => '2315101111',
            'prodi' => 'Teknologi Informasi',
            'angkatan' => '2024',
        ]);

        $kegiatan = Kegiatan::factory()->create([
            'tanggal' => now()->toDateString(),
        ]);

        KodeAbsensi::factory()->create([
            'kegiatan_id' => $kegiatan->id,
            'kode' => 'ABC123',
            'expired_at' => now()->addMinutes(10),
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('anggota.absensi.store'), [
            'kode' => 'abc123',
        ]);

        $response->assertRedirect(route('anggota.riwayat.index'));
        $this->assertDatabaseHas('absensi', [
            'user_id' => $user->id,
            'kegiatan_id' => $kegiatan->id,
            'status' => Absensi::STATUS_HADIR,
        ]);
        $this->assertNotNull(Absensi::query()->first()?->waktu_absen);
    }

    public function test_anggota_can_not_submit_an_expired_or_inactive_code(): void
    {
        $user = User::factory()->anggota()->create();
        $user->anggota()->create([
            'npm' => '2315101112',
            'prodi' => 'Teknologi Informasi',
            'angkatan' => '2024',
        ]);

        $kegiatan = Kegiatan::factory()->create([
            'tanggal' => now()->toDateString(),
        ]);

        KodeAbsensi::factory()->create([
            'kegiatan_id' => $kegiatan->id,
            'kode' => 'EXP123',
            'expired_at' => now()->subMinute(),
            'is_active' => true,
        ]);

        KodeAbsensi::factory()->create([
            'kegiatan_id' => $kegiatan->id,
            'kode' => 'OFF123',
            'expired_at' => now()->addMinutes(10),
            'is_active' => false,
        ]);

        $this->actingAs($user)
            ->from(route('anggota.absensi.create'))
            ->post(route('anggota.absensi.store'), ['kode' => 'EXP123'])
            ->assertRedirect(route('anggota.absensi.create'))
            ->assertSessionHasErrors('kode');

        $this->actingAs($user)
            ->from(route('anggota.absensi.create'))
            ->post(route('anggota.absensi.store'), ['kode' => 'OFF123'])
            ->assertRedirect(route('anggota.absensi.create'))
            ->assertSessionHasErrors('kode');

        $this->assertDatabaseCount('absensi', 0);
    }

    public function test_anggota_can_not_submit_the_same_attendance_code_twice(): void
    {
        $user = User::factory()->anggota()->create();
        $user->anggota()->create([
            'npm' => '2315101113',
            'prodi' => 'Teknologi Informasi',
            'angkatan' => '2024',
        ]);

        $kegiatan = Kegiatan::factory()->create([
            'tanggal' => now()->toDateString(),
        ]);

        KodeAbsensi::factory()->create([
            'kegiatan_id' => $kegiatan->id,
            'kode' => 'DUP123',
            'expired_at' => now()->addMinutes(10),
            'is_active' => true,
        ]);

        Absensi::factory()->create([
            'user_id' => $user->id,
            'kegiatan_id' => $kegiatan->id,
            'status' => Absensi::STATUS_HADIR,
            'waktu_absen' => now()->subMinute(),
        ]);

        $this->actingAs($user)
            ->from(route('anggota.absensi.create'))
            ->post(route('anggota.absensi.store'), ['kode' => 'DUP123'])
            ->assertRedirect(route('anggota.absensi.create'))
            ->assertSessionHasErrors('kode');

        $this->assertDatabaseCount('absensi', 1);
    }
}

<?php

namespace Database\Factories;

use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Absensi>
 */
class AbsensiFactory extends Factory
{
    protected $model = Absensi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->anggota(),
            'kegiatan_id' => Kegiatan::factory(),
            'status' => fake()->randomElement([
                Absensi::STATUS_HADIR,
                Absensi::STATUS_IZIN,
                Absensi::STATUS_ALFA,
            ]),
            'waktu_absen' => fake()->dateTimeBetween('-2 months', 'now'),
        ];
    }
}

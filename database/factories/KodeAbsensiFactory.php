<?php

namespace Database\Factories;

use App\Models\Kegiatan;
use App\Models\KodeAbsensi;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<KodeAbsensi>
 */
class KodeAbsensiFactory extends Factory
{
    protected $model = KodeAbsensi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kegiatan_id' => Kegiatan::factory(),
            'kode' => Str::upper(fake()->unique()->bothify('??##??')),
            'expired_at' => now()->addMinutes(15),
            'is_active' => true,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kegiatan>
 */
class KegiatanFactory extends Factory
{
    protected $model = Kegiatan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kegiatan' => fake()->sentence(3),
            'tanggal' => fake()->dateTimeBetween('-2 months', '+1 month'),
            'lokasi' => fake()->city(),
            'deskripsi' => fake()->paragraph(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Anggota>
 */
class AnggotaFactory extends Factory
{
    protected $model = Anggota::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->anggota(),
            'npm' => fake()->unique()->numerify('2315########'),
            'prodi' => fake()->randomElement(['Teknologi Informasi', 'Manajemen Informatika', 'Agribisnis']),
            'angkatan' => (string) fake()->numberBetween(2021, 2026),
        ];
    }
}

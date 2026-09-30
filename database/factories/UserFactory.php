<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Pabrik data identitas siswa untuk pengujian.
 *
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Nilai bawaan identitas siswa.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis' => fake()->unique()->numberBetween(10000000, 99999999),
            'nama' => fake()->name(),
            'rombel' => 'PPLG XI-'.fake()->numberBetween(1, 4),
        ];
    }
}

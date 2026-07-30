<?php

namespace Database\Factories;

use App\Models\Anggota;
use Illuminate\Database\Eloquent\Factories\Factory;

class SimpananFactory extends Factory
{
    public function definition(): array
    {
        return [
            'anggota_id' => Anggota::factory(),
            'jenis' => $this->faker->randomElement(['pokok', 'wajib', 'sukarela']),
            'saldo' => $this->faker->numberBetween(500_000, 5_000_000),
        ];
    }
}

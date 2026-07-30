<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnggotaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'anggota']),
            'nomor_anggota' => 'ANG-'.$this->faker->unique()->numerify('####'),
            'alamat' => $this->faker->address(),
            'status' => 'aktif',
        ];
    }
}

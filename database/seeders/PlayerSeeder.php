<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Player;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Player::insert([
            ['name' => 'Player1', 'balance' => 10000],
            ['name' => 'Player2', 'balance' => 10000],
            ['name' => 'Player3', 'balance' => 10000],
            ['name' => 'Player4', 'balance' => 10000],
        ]);
    }
}

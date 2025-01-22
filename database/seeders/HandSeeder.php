<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hand;

class HandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hand::insert([
            ['player_id' => 1, 'game_id' => 1, 'card_id' => 1],
            ['player_id' => 2, 'game_id' => 2, 'card_id' => 2],
            ['player_id' => 3, 'game_id' => 3, 'card_id' => 3],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Card;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Card::insert([
            ['suit' => '♥️', 'rank' => 'A'],
            ['suit' => '♥️', 'rank' => 2],
            ['suit' => '♥️', 'rank' => 3],
            ['suit' => '♥️', 'rank' => 4],
            ['suit' => '♥️', 'rank' => 5],
            ['suit' => '♥️', 'rank' => 6],
            ['suit' => '♥️', 'rank' => 7],
            ['suit' => '♥️', 'rank' => 8],
            ['suit' => '♥️', 'rank' => 9],
            ['suit' => '♥️', 'rank' => 10],
            ['suit' => '♥️', 'rank' => 'J'],
            ['suit' => '♥️', 'rank' => 'Q'],
            ['suit' => '♥️', 'rank' => 'K'],
            ['suit' => '♦️', 'rank' => 'A'],
            ['suit' => '♦️', 'rank' => 2],
            ['suit' => '♦️', 'rank' => 3],
            ['suit' => '♦️', 'rank' => 4],
            ['suit' => '♦️', 'rank' => 5],
            ['suit' => '♦️', 'rank' => 6],
            ['suit' => '♦️', 'rank' => 7],
            ['suit' => '♦️', 'rank' => 8],
            ['suit' => '♦️', 'rank' => 9],
            ['suit' => '♦️', 'rank' => 10],
            ['suit' => '♦️', 'rank' => 'J'],
            ['suit' => '♦️', 'rank' => 'Q'],
            ['suit' => '♦️', 'rank' => 'K'],
            ['suit' => '♣️', 'rank' => 'A'],
            ['suit' => '♣️', 'rank' => 2],
            ['suit' => '♣️', 'rank' => 3],
            ['suit' => '♣️', 'rank' => 4],
            ['suit' => '♣️', 'rank' => 5],
            ['suit' => '♣️', 'rank' => 6],
            ['suit' => '♣️', 'rank' => 7],
            ['suit' => '♣️', 'rank' => 8],
            ['suit' => '♣️', 'rank' => 9],
            ['suit' => '♣️', 'rank' => 10],
            ['suit' => '♣️', 'rank' => 'J'],
            ['suit' => '♣️', 'rank' => 'Q'],
            ['suit' => '♣️', 'rank' => 'K'],
            ['suit' => '♠️', 'rank' => 'A'],
            ['suit' => '♠️', 'rank' => 2],
            ['suit' => '♠️', 'rank' => 3],
            ['suit' => '♠️', 'rank' => 4],
            ['suit' => '♠️', 'rank' => 5],
            ['suit' => '♠️', 'rank' => 6],
            ['suit' => '♠️', 'rank' => 7],
            ['suit' => '♠️', 'rank' => 8],
            ['suit' => '♠️', 'rank' => 9],
            ['suit' => '♠️', 'rank' => 10],
            ['suit' => '♠️', 'rank' => 'J'],
            ['suit' => '♠️', 'rank' => 'Q'],
            ['suit' => '♠️', 'rank' => 'K'],
            ['suit' => 'back', 'rank' => 0],
        ]);
    }
}

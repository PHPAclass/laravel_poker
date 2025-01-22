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
            ['suit' => 'hearts', 'rank' => 1, 'image' => 'hearts1.png'],
            ['suit' => 'hearts', 'rank' => 2, 'image' => 'hearts2.png'],
            ['suit' => 'hearts', 'rank' => 3, 'image' => 'hearts3.png'],
            ['suit' => 'hearts', 'rank' => 4, 'image' => 'hearts4.png'],
            ['suit' => 'hearts', 'rank' => 5, 'image' => 'hearts5.png'],
            ['suit' => 'hearts', 'rank' => 6, 'image' => 'hearts6.png'],
            ['suit' => 'hearts', 'rank' => 7, 'image' => 'hearts7.png'],
            ['suit' => 'hearts', 'rank' => 8, 'image' => 'hearts8.png'],
            ['suit' => 'hearts', 'rank' => 9, 'image' => 'hearts9.png'],
            ['suit' => 'hearts', 'rank' => 10, 'image' => 'hearts10.png'],
            ['suit' => 'hearts', 'rank' => 11, 'image' => 'hearts11.png'],
            ['suit' => 'hearts', 'rank' => 12, 'image' => 'hearts12.png'],
            ['suit' => 'hearts', 'rank' => 13, 'image' => 'hearts13.png'],
            ['suit' => 'diamonds', 'rank' => 1, 'image' => 'diamonts1.png'],
            ['suit' => 'diamonds', 'rank' => 2, 'image' => 'diamonts2.png'],
            ['suit' => 'diamonds', 'rank' => 3, 'image' => 'diamonts3.png'],
            ['suit' => 'diamonds', 'rank' => 4, 'image' => 'diamonts4.png'],
            ['suit' => 'diamonds', 'rank' => 5, 'image' => 'diamonts5.png'],
            ['suit' => 'diamonds', 'rank' => 6, 'image' => 'diamonts6.png'],
            ['suit' => 'diamonds', 'rank' => 7, 'image' => 'diamonts7.png'],
            ['suit' => 'diamonds', 'rank' => 8, 'image' => 'diamonts8.png'],
            ['suit' => 'diamonds', 'rank' => 9, 'image' => 'diamonts9.png'],
            ['suit' => 'diamonds', 'rank' => 10, 'image' => 'diamonts10.png'],
            ['suit' => 'diamonds', 'rank' => 11, 'image' => 'diamonts11.png'],
            ['suit' => 'diamonds', 'rank' => 12, 'image' => 'diamonts12.png'],
            ['suit' => 'diamonds', 'rank' => 13, 'image' => 'diamonts13.png'],
            ['suit' => 'clubs', 'rank' => 1, 'image' => 'clubs1.png'],
            ['suit' => 'clubs', 'rank' => 2, 'image' => 'clubs2.png'],
            ['suit' => 'clubs', 'rank' => 3, 'image' => 'clubs3.png'],
            ['suit' => 'clubs', 'rank' => 4, 'image' => 'clubs4.png'],
            ['suit' => 'clubs', 'rank' => 5, 'image' => 'clubs5.png'],
            ['suit' => 'clubs', 'rank' => 6, 'image' => 'clubs6.png'],
            ['suit' => 'clubs', 'rank' => 7, 'image' => 'clubs7.png'],
            ['suit' => 'clubs', 'rank' => 8, 'image' => 'clubs8.png'],
            ['suit' => 'clubs', 'rank' => 9, 'image' => 'clubs9.png'],
            ['suit' => 'clubs', 'rank' => 10, 'image' => 'clubs10.png'],
            ['suit' => 'clubs', 'rank' => 11, 'image' => 'clubs11.png'],
            ['suit' => 'clubs', 'rank' => 12, 'image' => 'clubs12.png'],
            ['suit' => 'clubs', 'rank' => 13, 'image' => 'clubs13.png'],
            ['suit' => 'spades', 'rank' => 1, 'image' => 'spades1.png'],
            ['suit' => 'spades', 'rank' => 2, 'image' => 'spades2.png'],
            ['suit' => 'spades', 'rank' => 3, 'image' => 'spades3.png'],
            ['suit' => 'spades', 'rank' => 4, 'image' => 'spades4.png'],
            ['suit' => 'spades', 'rank' => 5, 'image' => 'spades5.png'],
            ['suit' => 'spades', 'rank' => 6, 'image' => 'spades6.png'],
            ['suit' => 'spades', 'rank' => 7, 'image' => 'spades7.png'],
            ['suit' => 'spades', 'rank' => 8, 'image' => 'spades8.png'],
            ['suit' => 'spades', 'rank' => 9, 'image' => 'spades9.png'],
            ['suit' => 'spades', 'rank' => 10, 'image' => 'spades10.png'],
            ['suit' => 'spades', 'rank' => 11, 'image' => 'spades11.png'],
            ['suit' => 'spades', 'rank' => 12, 'image' => 'spades12.png'],
            ['suit' => 'spades', 'rank' => 13, 'image' => 'spades13.png'],
            ['suit' => 'back', 'rank' => 0, 'image' => 'card_back.png'],
        ]);
    }
}

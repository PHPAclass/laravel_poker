<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameResultController;  // この行を追加

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('poker')->middleware(['web'])->group(function () {
    Route::get('/', function () {
        return view('poker', ['title' => 'Poker Game']);
    })->name('poker');
    Route::get('/start', [GameController::class, 'startGame'])->name('poker.start');
    Route::post('/call', [GameController::class, 'call'])->name('poker.call');
    Route::post('/raise', [GameController::class, 'raise'])->name('poker.raise');
    Route::post('/fold', [GameController::class, 'fold'])->name('poker.fold');
    Route::post('/check', [GameController::class, 'check'])->name('poker.check');
    Route::get('/result', [GameResultController::class, 'showResult'])->name('poker.result');
});
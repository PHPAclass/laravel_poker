<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get([GameController::class, 'game']);
Route::get('/poker',function(){
    return view("poker",["title"=>"poker"]);
})->name("poker");

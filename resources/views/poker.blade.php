@extends('layout.layout')

@section('title')
    {{$title}}
@endsection

@section('content')
    <main class="w-min-screen h-screen bg-gray-800 text-white px-12 flex flex-col justify-center gap-10">
        <section class="flex justify-center">
            <div class="">
                <p class="text-2xl text-center py-4">Player 3</p>
                <div class="flex gap-x-4">
                    <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                    <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                </div>
            </div>
        </section>
        <section class="flex justify-around items-end">
            <div class="">
                <div class="flex gap-x-4">
                    <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                    <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                </div>
                <p class="text-2xl text-center py-4">Player 2</p>
            </div>
        <div class="">
            <div class="flex gap-x-4">
                <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
            </div>
            <div class="flex text-2xl justify-around mt-4">
                <h3>誰のターン:who</h3>
                <h3>総ポット数:money</h3>
            </div>
        </div>
            <div class="">
                <div class="flex gap-x-4">
                    <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                    <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                </div>
                <p class="text-2xl text-center py-4">Player 4</p>
            </div>
        </section>
        <section class="flex justify-center">
            <div class="">
                <div class="flex gap-x-4">
                    <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                    <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                </div>
                <p class="text-2xl text-center py-4">Player 1</p>
            </div>
        </section>
        <form method="POST" action="{{ route('poker') }}" class="text-lg font-semibold text-center">
            <button type="submit" value="fold" name="opt">フォールト</button>
            <button type="submit" value="call" name="opt">コール</button>
            <button type="submit" value="raise" name="opt">レイズ</button>
        </form>
    </main>

@endsection
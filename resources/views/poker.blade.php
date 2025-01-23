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
                    @if(session('game.players.player3.status') !== 'fold')
                        <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                        <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                    @endif
                </div>
            </div>
        </section>
        <section class="flex justify-around items-end">
            <div class="">
                <div class="flex gap-x-4">
                    @if(session('game.players.player2.status') !== 'fold')
                        <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                        <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                    @endif
                </div>
                <p class="text-2xl text-center py-4">Player 2</p>
            </div>
            <div class="">
                <div class="flex gap-x-4">
                    @for($i = 0; $i < 5; $i++)
                        @if($i < session('game.dealer.revealed_cards'))
                            <div class="w-24 h-36 bg-neutral-100 text-black border-4 border-black relative">
                                <p class="absolute top-1 left-1">{{ session('game.dealer.card')[$i]['suit'] }}</p>
                                <p class="absolute top-1 left-7">{{ session('game.dealer.card')[$i]['rank'] }}</p>
                                <p class="absolute bottom-1 right-1 transform rotate-180">{{ session('game.dealer.card')[$i]['suit'] }}</p>
                                <p class="absolute bottom-1 right-7 transform rotate-180">{{ session('game.dealer.card')[$i]['rank'] }}</p>
                            </div>
                        @else
                            <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                        @endif
                    @endfor
                </div>
                <div class="flex text-2xl justify-around mt-4">
                    <h3>ターン: {{ session('game.dealer.current_player') }}</h3>
                    <h3>総ポット: {{ session('game.pot') }}</h3>
                </div>
            </div>
            <div class="">
                <div class="flex gap-x-4">
                    @if(session('game.players.player4.status') !== 'fold')
                        <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                        <div class="w-24 h-36 bg-green-800 border-4 border-black"></div>
                    @endif
                </div>
                <p class="text-2xl text-center py-4">Player 4</p>
            </div>
        </section>
        <section class="flex justify-center">
            <div class="">
                <div class="flex gap-x-4">
                    @foreach(session('game.players.player1.card') as $card)
                        <div class="w-24 h-36 bg-neutral-100 text-black border-4 border-black relative">
                            <p class="absolute top-1 left-1">{{ $card['suit'] }}</p>
                            <p class="absolute top-1 left-7">{{ $card['rank'] }}</p>
                            <p class="absolute bottom-1 right-1 transform rotate-180">{{ $card['suit'] }}</p>
                            <p class="absolute bottom-1 right-7 transform rotate-180">{{ $card['rank'] }}</p>
                        </div>
                    @endforeach
                </div>
                <p class="text-2xl text-center py-4">Player 1</p>
            </div>
        </section>
        <div class="text-lg font-semibold text-center">
            <form method="POST" action="{{ route('poker.fold') }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-500 rounded hover:bg-red-600">フォールド</button>
            </form>
            <form method="POST" action="{{ route('poker.call') }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-500 rounded hover:bg-blue-600">コール</button>
            </form>
            <form method="POST" action="{{ route('poker.raise') }}" class="inline">
                @csrf
                <input type="number" name="amount" class="px-2 py-1 text-black rounded" placeholder="レイズ額">
                <button type="submit" class="px-4 py-2 bg-green-500 rounded hover:bg-green-600">レイズ</button>
            </form>
        </div>
    </main>
@endsection
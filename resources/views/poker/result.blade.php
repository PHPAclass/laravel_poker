@extends('layout.layout')

@section('content')
<main class="w-full min-h-screen bg-gray-800 text-white px-12 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- 結果ヘッダー -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4">ゲーム終了</h1>
            <div class="text-3xl text-yellow-400">
                Player {{ $game['winner']['id'] }} の勝利！
            </div>
        </div>

        <!-- 勝者情報 -->
        <div class="bg-gray-700 rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">勝者情報</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-400">プレイヤー</p>
                    <p class="text-xl">Player {{ $game['winner']['id'] }}</p>
                </div>
                <div>
                    <p class="text-gray-400">獲得賞金</p>
                    <p class="text-xl text-green-400">{{ $game['winner']['winning_amount'] }} チップ</p>
                </div>
                <div>
                    <p class="text-gray-400">最終所持金</p>
                    <p class="text-xl">{{ $game['winner']['money'] }} チップ</p>
                </div>
                <div>
                    <p class="text-gray-400">役</p>
                    <p class="text-xl">{{ $game['winner']['hand_type'] }}</p>
                </div>
            </div>
        </div>

        <!-- 全プレイヤーの結果 -->
        <div class="bg-gray-700 rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">全プレイヤーの結果</h2>
            <div class="grid grid-cols-4 gap-4">
                @foreach($game['players'] as $key => $player)
                <div class="bg-gray-600 p-4 rounded">
                    <h3 class="font-bold mb-2">Player {{ substr($key, -1) }}</h3>
                    <p class="text-sm text-gray-300">最終所持金</p>
                    <p class="text-lg {{ $key === $game['winner']['id'] ? 'text-green-400' : '' }}">
                        {{ $player['money'] }} チップ
                    </p>
                    <p class="text-sm text-gray-300 mt-2">ステータス</p>
                    <p class="text-lg">{{ $player['status'] === 'fold' ? 'フォールド' : 'アクティブ' }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- コントロール -->
        <div class="flex justify-center gap-4">
            <form action="{{ route('poker.start') }}" method="GET">
                @csrf
                <button type="submit" class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    新しいゲームを開始
                </button>
            </form>
        </div>
    </div>
</main>
@endsection
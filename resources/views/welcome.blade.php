<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>ポーカー</title>
</head>
<body>
    <h1 class="text-red-400">ぽーかー</h1>
    <a href="{{ route('poker.start') }}" class="inline-block px-6 py-3 text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">Game Start</a>
    <img src="{{ asset('img/poker_logo.png') }}" alt="Poker Background">
</body>
</html>
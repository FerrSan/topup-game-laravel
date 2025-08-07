{{-- resources/views/games/search.blade.php --}}

@extends('layouts.app')

@section('title', 'Hasil Pencarian Game')

@section('content')
<div class="container mx-auto px-4 py-8 md:py-12 animate-fade-in">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-2 animate-slide-up">Hasil Pencarian</h1>
    <p class="text-xl text-gray-600 mb-8 animate-slide-up delay-100">Menampilkan hasil untuk: "<span class="text-indigo-600 font-semibold">{{ request('query') }}</span>"</p>

    @if($games->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6 md:gap-8 animate-fade-in delay-200">
            @foreach($games as $game)
                <a href="{{ route('games.show', $game->slug) }}" 
                   class="group block bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="relative h-48 sm:h-56">
                        <img src="{{ $game->image_url }}" alt="{{ $game->name }}" 
                             onerror="this.onerror=null;this.src='https://placehold.co/400x500/E5E7EB/9CA3AF?text={{ urlencode($game->name) }}';"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="font-bold text-sm text-gray-800 group-hover:text-indigo-600 transition-colors duration-300 line-clamp-1">
                            {{ $game->name }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-1">
                            {{ $game->publisher }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-12 flex justify-center animate-fade-in delay-300">
            {{ $games->links() }}
        </div>
    @else
        <div class="flex items-center justify-center h-64 bg-gray-100 rounded-2xl shadow-inner animate-fade-in delay-200">
            <p class="text-xl text-gray-500 font-medium">Maaf, tidak ada game yang cocok dengan pencarian Anda.</p>
        </div>
    @endif

    <div class="mt-12 text-center animate-fade-in delay-400">
        <a href="{{ route('games.index') }}" 
           class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition-colors duration-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Semua Game
        </a>
    </div>
</div>
@endsection

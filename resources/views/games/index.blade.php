{{-- resources/views/games/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Daftar Game Top-Up')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Daftar Game</h1>
        <div class="relative inline-block text-gray-700">
            <select onchange="window.location.href = this.value" 
                    class="block appearance-none w-full bg-white border border-gray-300 py-2 pl-3 pr-8 rounded-lg leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                <option value="{{ route('games.index') }}" {{ !request('category') ? 'selected' : '' }}>Semua Kategori</option>
                <option value="{{ route('games.index', ['category' => 'battle-royale']) }}" {{ request('category') == 'battle-royale' ? 'selected' : '' }}>Battle Royale</option>
                <option value="{{ route('games.index', ['category' => 'moba']) }}" {{ request('category') == 'moba' ? 'selected' : '' }}>MOBA</option>
                <option value="{{ route('games.index', ['category' => 'rpg']) }}" {{ request('category') == 'rpg' ? 'selected' : '' }}>RPG</option>
                <option value="{{ route('games.index', ['category' => 'casual']) }}" {{ request('category') == 'casual' ? 'selected' : '' }}>Casual</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                </svg>
            </div>
        </div>
    </div>
    
    @if($games->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            @foreach($games as $game)
                <a href="{{ route('games.show', $game->slug) }}" 
                   class="group block bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 transform hover:-translate-y-1">
                    <img src="{{ $game->image_url }}" alt="{{ $game->name }}" 
                         onerror="this.onerror=null;this.src='https://placehold.co/400x500/E5E7EB/9CA3AF?text=Game+Image'"
                         class="w-full h-40 object-cover rounded-t-xl group-hover:opacity-90 transition-opacity duration-300">
                    <div class="p-4 text-center">
                        <h3 class="font-semibold text-sm text-gray-800 group-hover:text-indigo-600 transition-colors duration-300 line-clamp-1">
                            {{ $game->name }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $game->publisher }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8 flex justify-center">
            {{ $games->links() }}
        </div>
    @else
        <div class="flex items-center justify-center h-64 bg-gray-50 rounded-lg">
            <p class="text-lg text-gray-500">Tidak ada game ditemukan saat ini.</p>
        </div>
    @endif
</div>
@endsection

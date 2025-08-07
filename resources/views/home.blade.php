<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'Top Up Game Termurah & Terpercaya')

@section('content')
    <!-- Hero Section with Banner Carousel -->
    <section class="relative">
        <div class="relative h-96 overflow-hidden bg-gradient-to-r from-purple-600 to-indigo-600">
            @if($banners->count() > 0)
                <div class="swiper-container h-full">
                    <div class="swiper-wrapper">
                        @foreach($banners as $banner)
                            <div class="swiper-slide">
                                <a href="{{ $banner->link }}" class="block h-full">
                                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" 
                                         class="w-full h-full object-cover">
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            @else
                <!-- Default Hero Content -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h1 class="text-5xl font-bold mb-4">Top Up Game Favoritmu</h1>
                        <p class="text-xl mb-8">Proses Cepat, Aman, dan Terpercaya</p>
                        <a href="#games" class="inline-flex items-center px-6 py-3 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-gray-100 transition">
                            Mulai Top Up
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Search Section -->
    <section class="py-8 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('search') }}" method="GET" class="flex justify-center">
                <div class="relative w-full max-w-2xl">
                    <input type="text" name="q" 
                           placeholder="Cari game favoritmu..." 
                           class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl text-lg focus:outline-none focus:border-indigo-500 transition"
                           value="{{ request('q') }}">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="absolute inset-y-0 right-0 px-6 bg-indigo-600 text-white rounded-r-xl hover:bg-indigo-700 transition">
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Popular Games -->
    @if($popularGames->count() > 0)
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900">🔥 Game Populer</h2>
                <a href="{{ route('games.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                    Lihat Semua →
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($popularGames as $game)
                    <a href="{{ route('games.show', $game->slug) }}" 
                       class="group relative bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="aspect-w-1 aspect-h-1">
                            <img src="{{ $game->image_url }}" alt="{{ $game->name }}" 
                                 class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                        </div>
                        <div class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            HOT
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-1">{{ $game->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $game->publisher }}</p>
                            <div class="mt-2 flex items-center text-xs text-gray-400">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H6a2 2 0 100 4h2a2 2 0 100-4h-.5a1 1 0 000-2H8a2 2 0 012-2V5z" clip-rule="evenodd"/>
                                </svg>
                                {{ $game->transactions_count }} transaksi
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- All Games Section -->
    <section id="games" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Pilih Game Favoritmu</h2>
                <p class="text-gray-600">Top up diamond, UC, VP, dan mata uang game lainnya dengan mudah</p>
            </div>
            
            <!-- Category Tabs -->
            <div class="flex justify-center mb-8">
                <div class="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-1">
                    <button data-category="all" class="category-tab px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none focus:bg-white focus:shadow-sm active">
                        Semua
                    </button>
                    <button data-category="mobile" class="category-tab px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none focus:bg-white focus:shadow-sm">
                        Mobile
                    </button>
                    <button data-category="pc" class="category-tab px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none focus:bg-white focus:shadow-sm">
                        PC
                    </button>
                    <button data-category="console" class="category-tab px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none focus:bg-white focus:shadow-sm">
                        Console
                    </button>
                </div>
            </div>
            
            <!-- Games Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($games as $game)
                    <div class="game-card" data-category="{{ $game->category }}">
                        <a href="{{ route('games.show', $game->slug) }}" 
                           class="group block bg-white rounded-lg shadow hover:shadow-lg transition-all duration-300">
                            <div class="relative overflow-hidden rounded-t-lg">
                                <img src="{{ $game->image_url }}" alt="{{ $game->name }}" 
                                     class="w-full h-40 object-cover group-hover:scale-110 transition-transform duration-300">
                                @if($game->products->where('is_promo', true)->count() > 0)
                                    <div class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">
                                        PROMO
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition">
                                    {{ $game->name }}
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $game->publisher }}</p>
                                <div class="mt-3 flex items-center justify-between">
                                    <span class="text-xs text-gray-400">
                                        <span class="inline-block w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                        Instant Process
                                    </span>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-10">
                <a href="{{ route('games.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                    Lihat Semua Game
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-white text-center mb-12">Kenapa Memilih Kami?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Proses Instan</h3>
                    <p class="text-white text-opacity-90">Top up otomatis masuk ke akun game kamu dalam hitungan detik</p>
                </div>
                
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">100% Aman</h3>
                    <p class="text-white text-opacity-90">Pembayaran terenkripsi dan data pribadi terlindungi</p>
                </div>
                
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white bg-opacity-20 rounded-full mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Harga Terbaik</h3>
                    <p class="text-white text-opacity-90">Dapatkan harga termurah dengan berbagai promo menarik</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    @if($testimonials->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Apa Kata Mereka?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($testimonials as $testimonial)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center mb-4">
                            <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}" 
                                 class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $testimonial->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $testimonial->game }}</p>
                            </div>
                        </div>
                        <div class="flex mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-gray-600">{{ $testimonial->content }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Siap Top Up Game Favoritmu?</h2>
            <p class="text-xl text-gray-600 mb-8">Bergabung dengan ribuan gamer yang sudah mempercayai kami</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('games.index') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                    Mulai Top Up Sekarang
                </a>
                <a href="{{ route('track.form') }}" 
                   class="inline-flex items-center justify-center px-8 py-4 bg-white text-indigo-600 font-semibold rounded-lg border-2 border-indigo-600 hover:bg-indigo-50 transition">
                    Lacak Pesanan
                </a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .category-tab.active {
        background-color: white;
        color: #4f46e5;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@push('scripts')
<script>
    // Category Filter
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.category-tab');
        const cards = document.querySelectorAll('.game-card');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Update active tab
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Filter games
                const category = this.dataset.category;
                cards.forEach(card => {
                    if (category === 'all' || card.dataset.category === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@endpush
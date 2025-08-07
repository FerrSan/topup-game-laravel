<!-- resources/views/games/show.blade.php -->
@extends('layouts.app')

@section('title', $game->name . ' - Top Up Termurah')

@section('content')
    <!-- Game Header -->
    <section class="bg-gradient-to-r from-purple-600 to-indigo-600 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-6">
                <img src="{{ $game->image_url }}" alt="{{ $game->name }}" 
                     class="w-24 h-24 rounded-lg shadow-lg">
                <div class="text-white">
                    <h1 class="text-3xl font-bold mb-2">{{ $game->name }}</h1>
                    <p class="text-white text-opacity-90">{{ $game->publisher }}</p>
                    <div class="flex items-center mt-2">
                        <span class="inline-block w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                        <span class="text-sm">Proses Otomatis 24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form id="topup-form" action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <input type="hidden" name="game_id" value="{{ $game->id }}">
                <input type="hidden" name="product_id" id="selected-product" value="">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column - Steps -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Step 1: Masukkan Data Akun -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-center mb-4">
                                <span class="flex items-center justify-center w-8 h-8 bg-indigo-600 text-white rounded-full font-semibold mr-3">1</span>
                                <h2 class="text-xl font-semibold">Masukkan Data Akun</h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($game->form_fields as $field)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $field['label'] }}
                                            @if($field['required'] ?? false)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </label>
                                        
                                        @if($field['type'] === 'select')
                                            <select name="{{ $field['name'] }}" 
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                                                    {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                                <option value="">Pilih {{ $field['label'] }}</option>
                                                @foreach($field['options'] as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="{{ $field['type'] ?? 'text' }}" 
                                                   name="{{ $field['name'] }}" 
                                                   placeholder="Masukkan {{ $field['label'] }}"
                                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500"
                                                   {{ ($field['required'] ?? false) ? 'required' : '' }}>
                                        @endif
                                    </div>
                                @endforeach
                                
                                <!-- Hidden fields for player data -->
                                <input type="hidden" name="player_id" id="player_id_hidden">
                                <input type="hidden" name="server_id" id="server_id_hidden">
                            </div>
                            
                            <p class="text-sm text-gray-500 mt-4">
                                ⚠️ Pastikan data akun yang kamu masukkan sudah benar. Kesalahan input bukan tanggung jawab kami.
                            </p>
                        </div>

                        <!-- Step 2: Pilih Nominal -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-center mb-4">
                                <span class="flex items-center justify-center w-8 h-8 bg-indigo-600 text-white rounded-full font-semibold mr-3">2</span>
                                <h2 class="text-xl font-semibold">Pilih Nominal Top Up</h2>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($products as $product)
                                    <label class="product-card cursor-pointer">
                                        <input type="radio" name="product" value="{{ $product->id }}" 
                                               data-price="{{ $product->price }}"
                                               data-name="{{ $product->name }}"
                                               class="hidden product-radio">
                                        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-indigo-500 transition">
                                            @if($product->is_promo)
                                                <div class="bg-green-500 text-white text-xs px-2 py-1 rounded mb-2 inline-block">
                                                    PROMO {{ $product->discount_percentage }}%
                                                </div>
                                            @endif
                                            
                                            <div class="font-semibold text-gray-900">
                                                {{ $product->amount }} {{ $product->currency_type }}
                                            </div>
                                            
                                            @if($product->bonus > 0)
                                                <div class="text-xs text-green-600">
                                                    +{{ $product->bonus }} Bonus
                                                </div>
                                            @endif
                                            
                                            <div class="mt-2">
                                                @if($product->original_price)
                                                    <div class="text-xs text-gray-400 line-through">
                                                        {{ $product->formatted_original_price }}
                                                    </div>
                                                @endif
                                                <div class="text-lg font-bold text-indigo-600">
                                                    {{ $product->formatted_price }}
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Step 3: Info Kontak (Optional) -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex items-center mb-4">
                                <span class="flex items-center justify-center w-8 h-8 bg-indigo-600 text-white rounded-full font-semibold mr-3">3</span>
                                <h2 class="text-xl font-semibold">Info Kontak (Opsional)</h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Email
                                    </label>
                                    <input type="email" name="player_email" 
                                           placeholder="email@example.com"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        No. WhatsApp
                                    </label>
                                    <input type="tel" name="player_phone" 
                                           placeholder="08123456789"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-500 mt-4">
                                💡 Isi email atau WhatsApp untuk mendapatkan notifikasi status pesanan
                            </p>
                        </div>
                    </div>

                    <!-- Right Column - Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                            <h3 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h3>
                            
                            <!-- Empty State -->
                            <div id="order-summary" class="space-y-3">
                                <div class="text-center py-8 text-gray-400">
                                    <svg class="w-16 h-16 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <p>Belum ada item dipilih</p>
                                </div>
                            </div>
                            
                            <!-- Order Details (Hidden by default) -->
                            <div id="order-details" class="hidden space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Game:</span>
                                    <span class="font-medium">{{ $game->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Item:</span>
                                    <span class="font-medium" id="selected-item">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">ID Player:</span>
                                    <span class="font-medium" id="player-info">-</span>
                                </div>
                                <hr class="my-3">
                                <div class="flex justify-between text-lg font-semibold">
                                    <span>Total:</span>
                                    <span class="text-indigo-600" id="total-price">Rp 0</span>
                                </div>
                            </div>
                            
                            <button type="submit" id="checkout-btn" disabled
                                    class="w-full mt-6 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-indigo-700 transition">
                                Lanjut ke Pembayaran
                            </button>
                            
                            <div class="mt-4 space-y-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Proses Otomatis
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    100% Aman
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Garansi 100%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Game Description -->
    <section class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Tentang {{ $game->name }}</h3>
                <p class="text-gray-600">{{ $game->description }}</p>
                
                <!-- How to Top Up -->
                <div class="mt-6">
                    <h4 class="font-semibold mb-3">Cara Top Up:</h4>
                    <ol class="list-decimal list-inside space-y-2 text-gray-600">
                        <li>Masukkan ID akun game kamu</li>
                        <li>Pilih nominal top up yang diinginkan</li>
                        <li>Pilih metode pembayaran</li>
                        <li>Lakukan pembayaran</li>
                        <li>Item akan otomatis masuk ke akun kamu</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Games -->
    @if($relatedGames->count() > 0)
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-xl font-semibold mb-6">Game Lainnya</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedGames as $related)
                    <a href="{{ route('games.show', $related->slug) }}" 
                       class="bg-white rounded-lg shadow hover:shadow-lg transition">
                        <img src="{{ $related->image_url }}" alt="{{ $related->name }}" 
                             class="w-full h-32 object-cover rounded-t-lg">
                        <div class="p-4">
                            <h4 class="font-semibold text-gray-900">{{ $related->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $related->publisher }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection

@push('styles')
<style>
    .product-card input[type="radio"]:checked + div {
        border-color: #4f46e5;
        background-color: #eef2ff;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('topup-form');
        const productRadios = document.querySelectorAll('.product-radio');
        const checkoutBtn = document.getElementById('checkout-btn');
        const orderSummary = document.getElementById('order-summary');
        const orderDetails = document.getElementById('order-details');
        const selectedItem = document.getElementById('selected-item');
        const totalPrice = document.getElementById('total-price');
        const playerInfo = document.getElementById('player-info');
        const selectedProductInput = document.getElementById('selected-product');
        
        // Get form fields based on game configuration
        const formFields = @json($game->form_fields);
        let playerIdField = null;
        let serverIdField = null;
        
        // Find the actual input fields dynamically
        formFields.forEach(field => {
            // Check for player ID fields (different games use different names)
            if (field.name === 'user_id' || field.name === 'player_id' || field.name === 'riot_id' || field.name === 'open_id') {
                playerIdField = document.querySelector(`input[name="${field.name}"], select[name="${field.name}"]`);
            }
            // Check for server/zone/additional fields
            if (field.name === 'zone_id' || field.name === 'server' || field.name === 'tagline') {
                serverIdField = document.querySelector(`input[name="${field.name}"], select[name="${field.name}"]`);
            }
        });
        
        // Update player info display
        function updatePlayerInfo() {
            let info = '';
            if (playerIdField && playerIdField.value) {
                info = playerIdField.value;
                // Set hidden field value
                document.getElementById('player_id_hidden').value = playerIdField.value;
            }
            if (serverIdField && serverIdField.value) {
                info += serverIdField.value ? ` (${serverIdField.value})` : '';
                // Set hidden field value
                document.getElementById('server_id_hidden').value = serverIdField.value;
            }
            playerInfo.textContent = info || '-';
            checkFormValidity();
        }
        
        // Listen to form field changes
        if (playerIdField) {
            playerIdField.addEventListener('input', updatePlayerInfo);
            playerIdField.addEventListener('change', updatePlayerInfo);
        }
        if (serverIdField) {
            serverIdField.addEventListener('input', updatePlayerInfo);
            serverIdField.addEventListener('change', updatePlayerInfo);
        }
        
        // Product selection handling
        productRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    const price = this.dataset.price;
                    const name = this.dataset.name;
                    
                    // Update selected product
                    selectedProductInput.value = this.value;
                    selectedItem.textContent = name;
                    totalPrice.textContent = 'Rp ' + parseInt(price).toLocaleString('id-ID');
                    
                    // Show order details, hide empty state
                    orderSummary.classList.add('hidden');
                    orderDetails.classList.remove('hidden');
                    
                    checkFormValidity();
                }
            });
        });
        
        // Check if form is valid and enable/disable checkout button
        function checkFormValidity() {
            const hasProduct = selectedProductInput.value !== '';
            const hasPlayerId = playerIdField && playerIdField.value !== '';
            
            // Check if server field is required and filled
            let hasRequiredServer = true;
            if (serverIdField && serverIdField.hasAttribute('required')) {
                hasRequiredServer = serverIdField.value !== '';
            }
            
            // Enable button only if all required fields are filled
            if (hasProduct && hasPlayerId && hasRequiredServer) {
                checkoutBtn.disabled = false;
            } else {
                checkoutBtn.disabled = true;
            }
        }
        
        // Form submission validation
        form.addEventListener('submit', function(e) {
            if (checkoutBtn.disabled) {
                e.preventDefault();
                alert('Harap lengkapi semua data yang diperlukan');
                return false;
            }
            
            // Validate all required fields
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Harap lengkapi semua field yang wajib diisi');
                return false;
            }
        });
        
        // Remove error styling when user types
        const allInputs = form.querySelectorAll('input, select');
        allInputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('border-red-500');
            });
        });
    });
</script>
@endpush
{{-- resources/views/transactions/track-form.blade.php --}}

@extends('layouts.app')

@section('title', 'Lacak Pesanan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
        <div class="md:flex">
            <div class="md:flex-shrink-0">
                {{-- Anda bisa menambahkan gambar yang relevan di sini jika diinginkan --}}
            </div>
            <div class="p-8">
                <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Lacak Pesanan Anda</div>
                <h2 class="block mt-1 text-lg leading-tight font-medium text-black">Masukkan Nomor Invoice</h2>
                <p class="mt-2 text-gray-500">Masukkan nomor invoice atau kode transaksi Anda di bawah ini untuk melacak status pesanan Anda.</p>
                
                <form action="{{ route('transactions.track') }}" method="GET" class="mt-4">
                    <div class="mb-4">
                        <label for="invoice_number" class="block text-gray-700 text-sm font-bold mb-2">
                            Nomor Invoice
                        </label>
                        <input type="text" id="invoice_number" name="invoice_number"
                               class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="Contoh: INV-20230815-12345" required>
                    </div>
                    @error('invoice_number')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                    <div class="flex items-center justify-between">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition-colors duration-200">
                            Lacak Pesanan
                        </button>
                    </div>
                </form>

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mt-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app') {{-- Menggunakan layout utama aplikasi Anda --}}

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Riwayat Transaksi Saya</h1>

    @if ($transactions->isEmpty())
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md" role="alert">
            <p class="font-bold">Tidak ada transaksi ditemukan.</p>
            <p class="mt-1">Anda belum melakukan transaksi apa pun. Yuk, jelajahi game dan top up sekarang!</p>
            <a href="{{ route('home') }}" class="text-blue-700 underline hover:text-blue-900 mt-3 inline-block">Mulai Top Up</a>
        </div>
    @else
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Invoice
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Game
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Produk
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jumlah (IDR)
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Metode Pembayaran
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th> {{-- Untuk kolom Aksi --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $transaction->invoice_number }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex items-center">
                                    @if ($transaction->game && $transaction->game->image_url)
                                        <div class="flex-shrink-0 w-8 h-8 mr-3">
                                            <img class="w-full h-full rounded-full object-cover"
                                                src="{{ $transaction->game->image_url }}"
                                                alt="{{ $transaction->game->name ?? 'Game Image' }}" />
                                        </div>
                                    @endif
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ $transaction->game->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $transaction->product->name ?? 'N/A' }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex items-center">
                                    @if ($transaction->paymentMethod && $transaction->paymentMethod->logo_url)
                                        <div class="flex-shrink-0 w-6 h-6 mr-2">
                                            <img class="w-full h-full object-contain"
                                                src="{{ $transaction->paymentMethod->logo_url }}"
                                                alt="{{ $transaction->paymentMethod->name ?? 'Payment Logo' }}" />
                                        </div>
                                    @endif
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ $transaction->paymentMethod->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                {{-- Menggunakan accessor getStatusBadgeAttribute() dari model Transaction --}}
                                {!! $transaction->status_badge !!}
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ $transaction->created_at->format('d M Y H:i') }}
                                </p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                <a href="{{ route('transactions.show', $transaction->invoice_number) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                    Detail
                                </a>
                                @if ($transaction->canBePaid())
                                    <a href="{{ route('payment.show', $transaction->invoice_number) }}" class="ml-3 text-green-600 hover:text-green-900 font-semibold">
                                        Bayar Sekarang
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                {{ $transactions->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
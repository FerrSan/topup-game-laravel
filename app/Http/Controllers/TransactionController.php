<?php 

// 5. TransactionController
// app/Http/Controllers/TransactionController.php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function index(Request $request)
    {
        $transactions = Transaction::with(['game', 'product', 'paymentMethod'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
            
        return view('transactions.index', compact('transactions'));
    }

    public function show($invoiceNumber)
    {
        $transaction = Transaction::with(['game', 'product', 'paymentMethod', 'logs'])
            ->where('invoice_number', $invoiceNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        return view('transactions.show', compact('transaction'));
    }

    public function track(Request $request)
    {
        $request->validate([
            'invoice' => 'required|string'
        ]);
        
        $transaction = Transaction::with(['game', 'product'])
            ->where('invoice_number', $request->invoice)
            ->orWhere('player_email', $request->invoice)
            ->first();
            
        if (!$transaction) {
            return back()->with('error', 'Transaksi tidak ditemukan.');
        }
        
        return view('transactions.track', compact('transaction'));
    }
}
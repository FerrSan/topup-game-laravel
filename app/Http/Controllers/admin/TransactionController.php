<?php 
// 9. Admin/TransactionController
// app/Http/Controllers/Admin/TransactionController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'game', 'product', 'paymentMethod']);
        
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('player_id', 'like', "%{$search}%")
                  ->orWhere('player_email', 'like', "%{$search}%");
            });
        }
        
        $transactions = $query->latest()->paginate(20);
        
        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'game', 'product', 'paymentMethod', 'logs']);
        
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,success,failed,expired,refunded',
            'notes' => 'nullable|string'
        ]);
        
        $oldStatus = $transaction->status;
        $transaction->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $transaction->notes
        ]);
        
        $transaction->addLog(
            'status_changed',
            "Status changed from {$oldStatus} to {$validated['status']} by admin"
        );
        
        return redirect()->route('admin.transactions.show', $transaction)
            ->with('success', 'Status transaksi berhasil diperbarui.');
    }
}
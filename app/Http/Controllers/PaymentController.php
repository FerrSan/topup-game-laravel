<?php

// 4. PaymentController
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentGateway;
    
    public function __construct(PaymentGatewayService $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function show($invoiceNumber)
    {
        $transaction = Transaction::with(['game', 'product', 'paymentMethod'])
            ->where('invoice_number', $invoiceNumber)
            ->firstOrFail();
            
        // Check if user is authorized to view this transaction
        if (auth()->check() && $transaction->user_id && $transaction->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Check if transaction is expired
        if ($transaction->isExpired()) {
            $transaction->update(['status' => 'expired']);
        }
        
        return view('payment.show', compact('transaction'));
    }

    public function callback(Request $request)
    {
        // Handle payment gateway callback
        $result = $this->paymentGateway->handleCallback($request->all());
        
        if ($result['status'] === 'success') {
            $transaction = Transaction::where('invoice_number', $result['invoice_number'])->first();
            
            if ($transaction) {
                $transaction->markAsPaid($result['data']);
                
                // Process the top-up (in real app, this would call game API)
                $this->processTopUp($transaction);
            }
            
            return redirect()->route('payment.success', $result['invoice_number']);
        }
        
        return redirect()->route('payment.failed', $result['invoice_number']);
    }

    public function webhook(Request $request)
    {
        // Handle payment gateway webhook (server-to-server notification)
        $result = $this->paymentGateway->handleWebhook($request->all());
        
        if ($result['status'] === 'success') {
            $transaction = Transaction::where('invoice_number', $result['invoice_number'])->first();
            
            if ($transaction && $transaction->isPending()) {
                $transaction->markAsPaid($result['data']);
                $this->processTopUp($transaction);
            }
        }
        
        return response()->json(['status' => 'ok']);
    }

    public function success($invoiceNumber)
    {
        $transaction = Transaction::with(['game', 'product'])
            ->where('invoice_number', $invoiceNumber)
            ->firstOrFail();
            
        return view('payment.success', compact('transaction'));
    }

    public function failed($invoiceNumber)
    {
        $transaction = Transaction::with(['game', 'product'])
            ->where('invoice_number', $invoiceNumber)
            ->firstOrFail();
            
        return view('payment.failed', compact('transaction'));
    }

    protected function processTopUp($transaction)
    {
        try {
            // In real application, this would call the game's API
            // to deliver the purchased items to the player
            
            // Simulate processing
            sleep(2);
            
            // Mark as success
            $transaction->markAsSuccess();
            
        } catch (\Exception $e) {
            $transaction->markAsFailed('Failed to process top-up: ' . $e->getMessage());
        }
    }
}
<?php

// 3. CheckoutController
// app/Http/Controllers/CheckoutController.php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Product;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Get checkout data from session
        $checkoutData = Session::get('checkout_data');
        
        if (!$checkoutData) {
            return redirect()->route('home')
                ->with('error', 'Sesi checkout telah berakhir. Silakan ulangi proses pembelian.');
        }
        
        $game = Game::findOrFail($checkoutData['game_id']);
        $product = Product::findOrFail($checkoutData['product_id']);
        $paymentMethods = PaymentMethod::active()->ordered()->get()->groupBy('type');
        
        return view('checkout.index', compact('game', 'product', 'checkoutData', 'paymentMethods'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'product_id' => 'required|exists:products,id',
            'player_id' => 'required|string',
            'server_id' => 'nullable|string',
            'player_name' => 'nullable|string',
            'player_phone' => 'nullable|string',
            'player_email' => 'nullable|email',
        ]);
        
        // Store checkout data in session
        Session::put('checkout_data', $validated);
        
        return redirect()->route('checkout.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);
        
        $checkoutData = Session::get('checkout_data');
        
        if (!$checkoutData) {
            return redirect()->route('home')
                ->with('error', 'Sesi checkout telah berakhir.');
        }
        
        DB::beginTransaction();
        
        try {
            $product = Product::findOrFail($checkoutData['product_id']);
            $paymentMethod = PaymentMethod::findOrFail($validated['payment_method_id']);
            
            // Calculate fees and total
            $amount = $product->price;
            $fee = $paymentMethod->calculateFee($amount);
            $totalAmount = $amount + $fee;
            
            // Create transaction
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'game_id' => $checkoutData['game_id'],
                'product_id' => $checkoutData['product_id'],
                'payment_method_id' => $paymentMethod->id,
                'player_id' => $checkoutData['player_id'],
                'server_id' => $checkoutData['server_id'] ?? null,
                'player_name' => $checkoutData['player_name'] ?? null,
                'player_phone' => $checkoutData['player_phone'] ?? null,
                'player_email' => $checkoutData['player_email'] ?? null,
                'amount' => $amount,
                'fee' => $fee,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            // Add transaction log
            $transaction->addLog('created', 'Transaction created');
            
            // Clear session
            Session::forget('checkout_data');
            
            DB::commit();
            
            // Redirect to payment page
            return redirect()->route('payment.show', $transaction->invoice_number);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->with('error', 'Terjadi kesalahan saat memproses transaksi.');
        }
    }
}
<?php

// app/Services/PaymentGatewayService.php

namespace App\Services;

use App\Models\Transaction;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService
{
    protected $config;
    
    public function __construct()
    {
        // In production, store these in .env file
        $this->config = [
            'tripay' => [
                'api_key' => env('TRIPAY_API_KEY'),
                'private_key' => env('TRIPAY_PRIVATE_KEY'),
                'merchant_code' => env('TRIPAY_MERCHANT_CODE'),
                'base_url' => env('TRIPAY_BASE_URL', 'https://tripayapp.com/api-sandbox'),
            ],
            'midtrans' => [
                'server_key' => env('MIDTRANS_SERVER_KEY'),
                'client_key' => env('MIDTRANS_CLIENT_KEY'),
                'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
                'base_url' => env('MIDTRANS_IS_PRODUCTION', false) 
                    ? 'https://app.midtrans.com/snap/v1' 
                    : 'https://app.sandbox.midtrans.com/snap/v1',
            ],
            'xendit' => [
                'secret_key' => env('XENDIT_SECRET_KEY'),
                'public_key' => env('XENDIT_PUBLIC_KEY'),
                'callback_token' => env('XENDIT_CALLBACK_TOKEN'),
                'base_url' => env('XENDIT_BASE_URL', 'https://api.xendit.co'),
            ]
        ];
    }

    /**
     * Create payment based on payment method type
     */
    public function createPayment(Transaction $transaction)
    {
        $paymentMethod = $transaction->paymentMethod;
        
        // For demo, we'll use a simple implementation
        // In production, you would integrate with actual payment gateways
        
        switch ($paymentMethod->type) {
            case 'e-wallet':
                return $this->createEWalletPayment($transaction);
            case 'virtual_account':
                return $this->createVirtualAccountPayment($transaction);
            case 'qris':
                return $this->createQRISPayment($transaction);
            case 'convenience_store':
                return $this->createRetailPayment($transaction);
            case 'credit_card':
                return $this->createCreditCardPayment($transaction);
            default:
                return $this->createGenericPayment($transaction);
        }
    }

    /**
     * Create E-Wallet Payment
     */
    protected function createEWalletPayment(Transaction $transaction)
    {
        // Example using Tripay API
        try {
            $signature = $this->generateTripaySignature($transaction);
            
            $payload = [
                'method' => $transaction->paymentMethod->code,
                'merchant_ref' => $transaction->invoice_number,
                'amount' => $transaction->total_amount,
                'customer_name' => $transaction->player_name ?? 'Customer',
                'customer_email' => $transaction->player_email ?? 'noreply@example.com',
                'customer_phone' => $transaction->player_phone ?? '081234567890',
                'order_items' => [
                    [
                        'sku' => $transaction->product->id,
                        'name' => $transaction->product->name . ' - ' . $transaction->game->name,
                        'price' => $transaction->amount,
                        'quantity' => 1,
                    ]
                ],
                'return_url' => route('payment.success', $transaction->invoice_number),
                'expired_time' => $transaction->expired_at->timestamp,
                'signature' => $signature
            ];

            // For demo purposes, we'll simulate the response
            // In production, you would make actual API call
            $response = $this->simulatePaymentResponse($transaction, 'e-wallet');
            
            // Update transaction with payment data
            $transaction->update([
                'payment_code' => $response['payment_code'] ?? null,
                'payment_url' => $response['payment_url'] ?? null,
                'payment_data' => $response
            ]);
            
            return [
                'success' => true,
                'data' => $response
            ];
            
        } catch (\Exception $e) {
            Log::error('E-Wallet payment creation failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to create payment'
            ];
        }
    }

    /**
     * Create Virtual Account Payment
     */
    protected function createVirtualAccountPayment(Transaction $transaction)
    {
        try {
            // For demo, generate a virtual account number
            $vaNumber = $this->generateVirtualAccountNumber($transaction);
            
            $response = [
                'payment_type' => 'virtual_account',
                'va_number' => $vaNumber,
                'bank' => strtoupper(str_replace('_va', '', $transaction->paymentMethod->code)),
                'amount' => $transaction->total_amount,
                'expired_at' => $transaction->expired_at->toIso8601String()
            ];
            
            $transaction->update([
                'payment_code' => $vaNumber,
                'payment_data' => $response
            ]);
            
            return [
                'success' => true,
                'data' => $response
            ];
            
        } catch (\Exception $e) {
            Log::error('VA payment creation failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to create virtual account'
            ];
        }
    }

    /**
     * Create QRIS Payment
     */
    protected function createQRISPayment(Transaction $transaction)
    {
        try {
            // For demo, generate QR code data
            $qrData = $this->generateQRISCode($transaction);
            
            $response = [
                'payment_type' => 'qris',
                'qr_string' => $qrData,
                'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrData),
                'amount' => $transaction->total_amount,
                'expired_at' => $transaction->expired_at->toIso8601String()
            ];
            
            $transaction->update([
                'payment_code' => substr($qrData, 0, 20),
                'payment_url' => $response['qr_url'],
                'payment_data' => $response
            ]);
            
            return [
                'success' => true,
                'data' => $response
            ];
            
        } catch (\Exception $e) {
            Log::error('QRIS payment creation failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to create QRIS payment'
            ];
        }
    }

    /**
     * Create Retail/Convenience Store Payment
     */
    protected function createRetailPayment(Transaction $transaction)
    {
        try {
            // Generate payment code for convenience store
            $paymentCode = $this->generateRetailPaymentCode($transaction);
            
            $response = [
                'payment_type' => 'convenience_store',
                'store' => $transaction->paymentMethod->name,
                'payment_code' => $paymentCode,
                'amount' => $transaction->total_amount,
                'expired_at' => $transaction->expired_at->toIso8601String()
            ];
            
            $transaction->update([
                'payment_code' => $paymentCode,
                'payment_data' => $response
            ]);
            
            return [
                'success' => true,
                'data' => $response
            ];
            
        } catch (\Exception $e) {
            Log::error('Retail payment creation failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to create retail payment'
            ];
        }
    }

    /**
     * Create Credit Card Payment
     */
    protected function createCreditCardPayment(Transaction $transaction)
    {
        try {
            // For credit card, typically you would get a payment URL from Midtrans Snap
            $token = $this->generatePaymentToken($transaction);
            
            $response = [
                'payment_type' => 'credit_card',
                'token' => $token,
                'payment_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $token,
                'amount' => $transaction->total_amount,
                'expired_at' => $transaction->expired_at->toIso8601String()
            ];
            
            $transaction->update([
                'payment_url' => $response['payment_url'],
                'payment_data' => $response
            ]);
            
            return [
                'success' => true,
                'data' => $response
            ];
            
        } catch (\Exception $e) {
            Log::error('Credit card payment creation failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to create credit card payment'
            ];
        }
    }

    /**
     * Generic payment creation
     */
    protected function createGenericPayment(Transaction $transaction)
    {
        $response = [
            'payment_type' => 'generic',
            'amount' => $transaction->total_amount,
            'expired_at' => $transaction->expired_at->toIso8601String()
        ];
        
        $transaction->update([
            'payment_data' => $response
        ]);
        
        return [
            'success' => true,
            'data' => $response
        ];
    }

    /**
     * Handle payment callback from payment gateway
     */
    public function handleCallback($data)
    {
        // Verify signature/callback authenticity
        if (!$this->verifyCallbackSignature($data)) {
            return [
                'status' => 'failed',
                'message' => 'Invalid signature'
            ];
        }
        
        // Get invoice number from callback data
        $invoiceNumber = $data['merchant_ref'] ?? $data['order_id'] ?? $data['external_id'] ?? null;
        
        if (!$invoiceNumber) {
            return [
                'status' => 'failed',
                'message' => 'Invoice number not found'
            ];
        }
        
        // Get payment status
        $status = $this->parsePaymentStatus($data);
        
        return [
            'status' => $status,
            'invoice_number' => $invoiceNumber,
            'data' => $data
        ];
    }

    /**
     * Handle webhook notification from payment gateway
     */
    public function handleWebhook($data)
    {
        return $this->handleCallback($data);
    }

    /**
     * Simulate payment response for demo
     */
    protected function simulatePaymentResponse(Transaction $transaction, $type)
    {
        switch ($type) {
            case 'e-wallet':
                return [
                    'payment_url' => 'https://payment-simulator.example.com/pay/' . $transaction->invoice_number,
                    'payment_code' => strtoupper(substr(md5($transaction->invoice_number), 0, 8)),
                    'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . $transaction->invoice_number
                ];
            default:
                return [
                    'payment_code' => strtoupper(substr(md5($transaction->invoice_number), 0, 12)),
                ];
        }
    }

    /**
     * Generate signature for Tripay
     */
    protected function generateTripaySignature(Transaction $transaction)
    {
        $merchantCode = $this->config['tripay']['merchant_code'];
        $merchantRef = $transaction->invoice_number;
        $amount = $transaction->total_amount;
        $privateKey = $this->config['tripay']['private_key'];
        
        return hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey);
    }

    /**
     * Generate virtual account number
     */
    protected function generateVirtualAccountNumber(Transaction $transaction)
    {
        $bankCode = [
            'bca_va' => '80777',
            'bni_va' => '8848',
            'bri_va' => '88680',
            'mandiri_va' => '88708',
            'permata_va' => '8528'
        ];
        
        $prefix = $bankCode[$transaction->paymentMethod->code] ?? '99999';
        $uniqueCode = str_pad($transaction->id, 10, '0', STR_PAD_LEFT);
        
        return $prefix . $uniqueCode;
    }

    /**
     * Generate QRIS code
     */
    protected function generateQRISCode(Transaction $transaction)
    {
        // In production, this would be actual QRIS string from payment gateway
        return 'ID1020210001' . $transaction->invoice_number . sprintf('%012d', $transaction->total_amount);
    }

    /**
     * Generate retail payment code
     */
    protected function generateRetailPaymentCode(Transaction $transaction)
    {
        $prefix = $transaction->paymentMethod->code === 'indomaret' ? '1234' : '5678';
        return $prefix . str_pad($transaction->id, 10, '0', STR_PAD_LEFT);
    }

    /**
     * Generate payment token
     */
    protected function generatePaymentToken(Transaction $transaction)
    {
        return base64_encode($transaction->invoice_number . '|' . $transaction->total_amount);
    }

    /**
     * Verify callback signature
     */
    protected function verifyCallbackSignature($data)
    {
        // Implementation depends on payment gateway
        // For demo, we'll return true
        return true;
    }

    /**
     * Parse payment status from callback data
     */
    protected function parsePaymentStatus($data)
    {
        $status = $data['status'] ?? $data['transaction_status'] ?? null;
        
        $successStatuses = ['paid', 'success', 'settlement', 'capture'];
        $pendingStatuses = ['pending', 'authorize'];
        $failedStatuses = ['failed', 'deny', 'cancel', 'expire'];
        
        if (in_array(strtolower($status), $successStatuses)) {
            return 'success';
        } elseif (in_array(strtolower($status), $pendingStatuses)) {
            return 'pending';
        } elseif (in_array(strtolower($status), $failedStatuses)) {
            return 'failed';
        }
        
        return 'unknown';
    }
}
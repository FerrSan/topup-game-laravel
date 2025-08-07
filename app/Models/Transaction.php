<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'game_id',
        'product_id',
        'payment_method_id',
        'player_id',
        'server_id',
        'player_name',
        'player_phone',
        'player_email',
        'amount',
        'fee',
        'total_amount',
        'payment_code',
        'payment_url',
        'payment_data',
        'status',
        'paid_at',
        'expired_at',
        'notes',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_data' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->invoice_number)) {
                $transaction->invoice_number = self::generateInvoiceNumber();
            }
            if (empty($transaction->expired_at)) {
                $transaction->expired_at = now()->addHours(24);
            }
        });
    }

    public static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(6));
        
        return $prefix . $date . $random;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function logs()
    {
        return $this->hasMany(TransactionLog::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isSuccess()
    {
        return $this->status === 'success';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isExpired()
    {
        return $this->status === 'expired' || 
               ($this->isPending() && $this->expired_at && $this->expired_at->isPast());
    }

    public function canBePaid()
    {
        return $this->isPending() && !$this->isExpired();
    }

    public function markAsPaid($paymentData = [])
    {
        $this->update([
            'status' => 'processing',
            'paid_at' => now(),
            'payment_data' => array_merge($this->payment_data ?? [], $paymentData)
        ]);

        $this->addLog('payment_received', 'Payment received from customer');
    }

    public function markAsSuccess()
    {
        $this->update(['status' => 'success']);
        $this->addLog('completed', 'Transaction completed successfully');
    }

    public function markAsFailed($reason = null)
    {
        $this->update(['status' => 'failed']);
        $this->addLog('failed', $reason ?? 'Transaction failed');
    }

    public function addLog($action, $description = null, $data = [])
    {
        return $this->logs()->create([
            'action' => $action,
            'description' => $description,
            'data' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Menunggu Pembayaran</span>',
            'processing' => '<span class="px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">Diproses</span>',
            'success' => '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Berhasil</span>',
            'failed' => '<span class="px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Gagal</span>',
            'expired' => '<span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded-full">Kadaluarsa</span>',
            'refunded' => '<span class="px-2 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">Refund</span>',
        ];

        return $badges[$this->status] ?? $this->status;
    }
}
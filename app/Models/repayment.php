<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'amount_paid',
        'balance_after_payment',
        'payment_date',
        'status'
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    // Each repayment belongs to one loan
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'id');
    }
}

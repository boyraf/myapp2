<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'amount',
        'interest_rate',
        'repayment_period',
        'status',
        'issue_date',
        'due_date',
        'balance'
    ];
    public function scopeActive($query)
    {
        // Scope to get approved loans that are not yet paid (active status)
        return $query->where('status', 'approved');
    }
    
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'id');
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class, 'loan_id', 'id');
    }

    public function guarantors()
    {
        return $this->hasMany(Guarantor::class, 'loan_id', 'id');
    }

    /**
     * Calculate simple monthly interest on current balance.
     * Formula: (balance * interest_rate / 100) / 12
     */
    public function monthlyInterest(): float
    {
        return round(($this->balance * $this->interest_rate / 100) / 12, 2);
    }
}

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
        return $query->where('status', 'active');
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
}

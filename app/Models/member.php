<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'id_number',
        'date_of_birth',
        'address',
        'membership_date',
        'status'
    ];

    // Relationships
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function savings()
    {
        return $this->hasMany(Saving::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function totalRepayments()
{
    return $this->hasManyThrough(
        Repayment::class,  // final model
        Loan::class,       // intermediate model
        'member_id',       // Foreign key on Loan table...
        'loan_id',         // Foreign key on Repayment table...
        'id',              // Local key on Member table
        'id'               // Local key on Loan table
    );
}
}

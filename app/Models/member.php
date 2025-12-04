<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'id_number',
        'date_of_birth',
        'address',
        'membership_date',
        'status',
        'shares'
    ];

    protected $hidden = [
        'password',
        'remember_token',
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

    public function guarantees()
    {
        // guarantees the member has made for others' loans
        return $this->hasMany(Guarantor::class, 'guarantor_id', 'id');
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

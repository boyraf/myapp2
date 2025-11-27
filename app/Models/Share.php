<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'quantity',
        'price_per_share',
        'total_value',
        'acquired_at',
        'status',
        'controlled_by_admin',
    ];

    protected $casts = [
        'acquired_at' => 'datetime',
        'controlled_by_admin' => 'boolean',
        'price_per_share' => 'decimal:2',
        'total_value' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

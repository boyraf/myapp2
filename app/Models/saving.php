<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'amount',
        'type',
        'balance_after',
        'status',
        'date'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'amount',
        'type',
        'balance_after',
        'status',
        'date'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'amount',
        'type',
        'balance_after',
        'status',
        'date'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

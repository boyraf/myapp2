<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'member_id',
        'action',
        'table_name',
        'record_id',
        'old_values',
        'new_values',
        'details',
        'ip_address'
    ];

    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'admin_id');
    }

    public function member()
    {
        return $this->belongsTo(\App\Models\Member::class, 'member_id');
    }
}

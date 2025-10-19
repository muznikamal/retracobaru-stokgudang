<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_name',
        'device_token',
        'user_agent',
        'ip_address',
        'last_login_at',
        'is_approved',
    ];

    protected $casts = [
        'last_login_at' => 'datetime', // 🩵 tambahkan baris ini!
        // 'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

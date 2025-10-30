<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangOpname extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'keterangan',
        'user_id',
        'tanggal_opname',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function opname()
    {
        return $this->hasMany(BarangOpname::class);
    }

}

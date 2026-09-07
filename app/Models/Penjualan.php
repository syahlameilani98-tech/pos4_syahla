<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

   protected $fillable = [
    'user_id',
    'total_pembayaran',
    'uang_masuk',
    'kembalian',
    'metode_pembayaran',
    'status'
];

    public function itemPenjualan()
    {
        return $this->hasMany(
            ItemPenjualan::class,
            'penjualan_id',
            'id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
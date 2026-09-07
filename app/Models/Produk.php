<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Jenis;
use App\Models\ItemPenjualan;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'user_id',
        'jenis_id',
        'name',
        'harga_beli',
        'harga_jual',
        'stok',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jenis()
    {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }

    public function itemPenjualan()
    {
        return $this->hasMany(ItemPenjualan::class, 'produk_id');
    }
}
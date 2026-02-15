<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = ['nama', 'harga','catatan', 'jam_ambil', 'tanggal_ambil', 'transaksi', 'status', 'tanggal_income'];

    protected $casts = [
        'tanggal_ambil' => 'date',
        'tanggal_income' => 'date',
    ];

    // Relasi ke Income
    public function income() {
        return $this->belongsTo(Income::class, 'tanggal_income', 'tanggal_income');
    }

    public function getTanggalAmbilAttribute($value)
{
    return \Carbon\Carbon::parse($value)->format('Y-m-d');
}

}

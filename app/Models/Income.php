<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $primaryKey = 'tanggal_income';
    public $incrementing = false;
    protected $keyType = 'date';

    protected $fillable = ['tanggal_income', 'gopay', 'bsi', 'cash', 'total', 'month_year','total_outcome'];

    protected $casts = [
        'tanggal_income' => 'date',
        'month_year' => 'date',
    ];

    // Relasi ke Summary
    public function summary() {
        return $this->belongsTo(Summary::class, 'month_year', 'month_year');
    }

       public function getTanggalIncomeAttribute($value)
{
    return \Carbon\Carbon::parse($value)->format('Y-m-d');
}

    public function getMonthYearAttribute($value)
{
    return \Carbon\Carbon::parse($value)->format('Y-m-d');
}
}

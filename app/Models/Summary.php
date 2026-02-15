<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summary extends Model
{
    use HasFactory;

    protected $primaryKey = 'month_year';
    public $incrementing = false; // Karena primary key berupa date
    protected $keyType = 'date ';

    protected $fillable = ['month_year', 'total_income', 'total_outcome','total'];

    // Relasi ke Income
    public function incomes() {
        return $this->hasMany(Income::class, 'month_year', 'month_year');
    }

    // Relasi ke Outcome
    public function outcomes() {
        return $this->hasMany(Outcome::class, 'month_year', 'month_year');
    }

       public function getMonthYearAttribute($value)
{
    return \Carbon\Carbon::parse($value)->format('Y-m-d');
}
}

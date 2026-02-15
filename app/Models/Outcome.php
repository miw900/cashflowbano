<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outcome extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = ['tanggal', 'total', 'keterangan', 'tanggal_income', 'month_year'];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_income' => 'date',
        'month_year' => 'date',
    ];

    protected static function booted()
    {
        static::created(function ($outcome) {
            self::updateTotalOutcome($outcome->tanggal_income);
        });

        static::updated(function ($outcome) {
            self::updateTotalOutcome($outcome->tanggal_income);
        });

        static::deleted(function ($outcome) {
            self::updateTotalOutcome($outcome->tanggal_income);
        });
    }

    public static function updateTotalOutcome($tanggal_income)
    {
        $totalOutcome = self::where('tanggal_income', $tanggal_income)->sum('total');
        $income = Income::where('tanggal_income', $tanggal_income)->first();
        if ($income) {
            $income->total_outcome = $totalOutcome;
            $income->save();
        }
    }

    public function summary() {
        return $this->belongsTo(Summary::class, 'month_year', 'month_year');
    }

    public function income() {
        return $this->belongsTo(Income::class, 'tanggal_income', 'tanggal_income');
    }
   public function getTanggalAttribute($value)
{
    return \Carbon\Carbon::parse($value)->format('Y-m-d');
}
}


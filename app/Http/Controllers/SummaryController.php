<?php

namespace App\Http\Controllers;

use App\Models\Summary;
use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Outcome;
use App\Models\Order;

use Carbon\Carbon;
class SummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $query = Summary::query(); // Start with a query builder instance
        $summary = $query->orderBy('month_year', 'DESC')->paginate(10);        

        return view('summary', compact('summary'));        

    }

    public function dashboard()
    {

        // Mendapatkan bulan dan tahun saat ini
        $currentMonthYear = Carbon::now()->format('Y-m-01');        
        // Bulan dan tahun saat ini
        $currentMonth = Carbon::now()->format('Y-m');
        // Bulan dan tahun bulan depan
        $nextMonth = Carbon::now()->addMonth()->format('Y-m');

        // Jumlah orderan pending untuk bulan ini dan bulan depan
        $pendingOrders = Order::where('status', 'pending')
            ->where(function($query) use ($currentMonth, $nextMonth) {
                $query->where('tanggal_income', 'like', $currentMonth . '%')
                    ->orWhere('tanggal_income', 'like', $nextMonth . '%');
            })
            ->count();

        // Jumlah orderan yang selesai di bulan ini
        $completedOrders = Order::where('status', 'done')
            ->where('tanggal_income', 'like', $currentMonth . '%')
            ->count();        
        
        // Query untuk mendapatkan data hanya di bulan ini
        $summary = Summary::where('month_year', $currentMonthYear)->first();          
        if (!$summary) {
    $summary = new Summary();
    $summary->month_year = $currentMonthYear;
    $summary->value = 0; // Atur nilai default
}

        return view('dashboard', compact('summary','pendingOrders', 'completedOrders'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Get the month and year input
        $monthYear = $request->input('date');

        // Format the monthYear to always have the first day of the month
        $monthYear = Carbon::createFromFormat('Y-m', $monthYear)->format('Y-m-01');

        // Retrieve all daily income records for the given month-year
        $incomes = Income::where('month_year', $monthYear)->get();

        // Calculate the total income and total outcome for the month
        $totalIncome = $incomes->sum('total');
        $totalOutcome = $incomes->sum('total_outcome');
        $total = $totalIncome - $totalOutcome;

        // Save the aggregated data into the summaries table
        Summary::updateOrCreate(
            ['month_year' => $monthYear],
            [
                'total_income' => $totalIncome,
                'total_outcome' => $totalOutcome,
                'total' => $total,
            ]
        );

        return redirect()->route('income')->with('success', 'Summary  has been saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Summary $summary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $summary = Summary::findOrFail($id);
        return view('editSummary', compact('summary'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request )
    {
        //
        $request->validate([
            'total_income' => 'required',
            'total_outcome' => 'required',
        ]);

        $summary = Summary::findOrFail($request->id);

        // Hitung total berdasarkan total_income dan total_outcome
        $summary->total_income = $request->total_income;
        $summary->total_outcome = $request->total_outcome;
        $summary->total = $request->total_income - $request->total_outcome;

        $summary->save();        
        return redirect()->route('summary')->with('success', 'Summary updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $summary = Summary::findOrFail($id);
        $summary->delete();
        return redirect()->route('summary')->with('success', 'Summary deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Outcome;
use App\Http\Requests\StoreOutcomeRequest;
use App\Http\Requests\UpdateOutcomeRequest;
use Illuminate\Http\Request;
use App\Models\Income;
use Carbon\Carbon;

class OutcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
     $query = Outcome::query(); // Start with a query builder instance    

    if ($request->has('cari') && !empty($request->cari)) {
            $query->where('keterangan', 'like', '%' . $request->cari . '%');
        }

    if ($request->filled('start_date') || $request->filled('end_date')) {
        $start_date = $request->filled('start_date') ? $request->start_date : null;
        $end_date = $request->filled('end_date') ? $request->end_date : now()->toDateString();

        if (!$start_date) {
            $start_date = date(01-01-1999);
        }

        $query->whereBetween('tanggal', [$start_date, $end_date]);
    }

        // Paginate the query result
        $outcome = $query->orderBy('tanggal','DESC')->paginate(10); 
        return view('outcome', compact('outcome'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('addOutcome');
    }

    /**
     * Store a newly created resource in storage.
     */
     // Method to create a new outcome
    public function store(Request $request)
    {
        $request->validate([
            
            'total' => 'required',
            'keterangan' => 'required',
            'tanggal' => 'required',
        ]);

        // Set tanggal_income and month_year to the same value as tanggal
        $tanggal_income = $request->tanggal;
        $month_year = date('Y-m-01', strtotime($request->tanggal)); // Set month_year to the first day of the month
        $outcome = Outcome::create([
            
            'total' => $request->total,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
            'tanggal_income' => $tanggal_income,
            'month_year' => $month_year,
        ]);


        // Update the incomes table
        $tanggal_income = Carbon::parse($outcome->tanggal)->format('Y-m-d');
        $month_year = Carbon::parse($outcome->tanggal)->format('Y-m');

        $income = Income::firstOrNew(['tanggal_income' => $tanggal_income]);
        $income->month_year = $month_year;
        $income->total_outcome += $outcome->total;
        // Update the total column
        $income->total = $income->cash + $income->gopay + $income->bsi;

        $income->save();
        // Update total_outcome in the corresponding Income record
        $this->updateTotalOutcome($outcome->tanggal_income);

        return redirect()->route('outcome')->with('success', 'Outcome created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Outcome $Outcome)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $Outcome = Outcome::findOrFail($id);
        return view('editOutcome', compact('Outcome'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            
            'total' => 'required|numeric',
            'keterangan' => 'required|string',
        ]);

        $outcome = Outcome::findOrFail($id);

        // Set tanggal_income and month_year to the same value as tanggal
        $tanggal_income = $request->tanggal;
        $month_year = date('Y-m-01', strtotime($request->tanggal)); // Set month_year to the first day of the month

        $outcome->update([            
            'total' => $request->total,
            'keterangan' => $request->keterangan,
            'tanggal' => $request->tanggal,
            'tanggal_income' => $tanggal_income,
            'month_year' => $month_year,
        ]);

        // Update total in the corresponding Income record
        $this->updateTotalOutcome($outcome->tanggal_income);

        return redirect()->route('outcome')->with('success', 'Outcome updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    // Method to delete an outcome
    public function destroy($id)
    {
        $outcome = Outcome::findOrFail($id);
        $outcome->delete();
        $tanggal_income = $outcome->tanggal_income;

        // Update total in the corresponding Income record
        $this->updateTotalOutcome($tanggal_income);

        return redirect()->route('outcome')->with('success', 'Outcome deleted successfully.');
    }

    // Method to update total in the Income model
    protected function updateTotalOutcome($tanggal_income)
    {
        $totalOutcome = Outcome::where('tanggal_income', $tanggal_income)->sum('total');
        $income = Income::where('tanggal_income', $tanggal_income)->first();
        if ($income) {
            $income->total_outcome = $totalOutcome;
            $income->save();
        }
    }
}

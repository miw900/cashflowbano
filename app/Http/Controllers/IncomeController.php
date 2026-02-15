<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use Illuminate\Http\Request;
use App\Models\Summary;
use App\Models\Order;
use App\Models\Outcome;
use Carbon\Carbon;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //

        $query = Income::query(); // Start with a query builder instance

        // Apply filter if search parameter is present
        if ($request->filled('start_date') || $request->filled('end_date')) {
        $start_date = $request->filled('start_date') ? $request->start_date : null;
        $end_date = $request->filled('end_date') ? $request->end_date : now()->toDateString();

        if (!$start_date) {
            $start_date = date(01-01-1999);
        }

        $query->whereBetween('tanggal_income', [$start_date, $end_date]);
    }

        // Paginate the query result
        $income = $query->orderBy('tanggal_income', 'DESC')->paginate(10);
        return view('income', compact('income'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $id)
    {
        //        
        // Validate the payment method input
        $request->validate([
            'payment_method' => 'required|string|in:cash,gopay,bsi',
        ]);

        // Find the order by ID
        $order = Order::findOrFail($id);

        // Update the order's transaksi and status attributes
        $order->transaksi = $request->input('payment_method');
        $order->status = 'done';
        // Redirect back to the order page
        $order->save();
        

        // Update the incomes table
        $tanggal_income = Carbon::parse($order->tanggal_ambil)->format('Y-m-d');
        $month_year = Carbon::parse($order->tanggal_ambil)->format('Y-m');

       $income = Income::firstOrNew(['tanggal_income' => $tanggal_income]);
        $income->month_year = $month_year;
        $income->total_outcome += 0;

        

        // Add the order's harga to the appropriate column based on the payment method
        switch ($request->input('payment_method')) {
            case 'cash':
                $income->cash += $order->harga;
                break;
            case 'gopay':
                $income->gopay += $order->harga;
                break;
            case 'bsi':
                $income->bsi += $order->harga;
                break;
        }

         // Update the total column
        $income->total = $income->cash + $income->gopay + $income->bsi;

        $income->save();

        // Format the monthYear to always have the first day of the month
        $monthYear = Carbon::createFromFormat('Y-m', $month_year)->format('Y-m-01');

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
   

        try {
                 
            return redirect()->route('orders')->with('success', 'pesanan selesei');
        } catch (\Exception $e) {
            return redirect()->route('orders')->with('error', 'pesanan gagal');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $Income)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($tanggal_income)
    {
        //
        $income = Income::where('tanggal_income', $tanggal_income)->firstOrFail();
        return view('editIncome', compact('income'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $tanggal_income)
    {
        //
        $request->validate([
            'gopay' => 'required|numeric',
            'bsi' => 'required|numeric',
            'cash' => 'required|numeric',
            'total' => 'required|numeric',
        ]);

        $income = Income::where('tanggal_income', $tanggal_income)->firstOrFail();
        $income->gopay = $request->gopay;
        $income->bsi = $request->bsi;
        $income->cash = $request->cash;
        $income->total = $request->total;
        $income->save();

        return redirect()->route('income')->with('success', 'Income updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($tanggal_income)
    {
        //
        $income = Income::findOrFail($tanggal_income);
        $order = Order::where('tanggal_ambil', $tanggal_income);
        $outcome = Outcome::where('tanggal', $tanggal_income);
        try {
            $income->delete();
            $order->delete();
            $outcome->delete();
            return redirect()->route('income')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('income')->with('error', 'Data gagal dihapus');
        }
    }
}

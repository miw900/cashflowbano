<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreorderRequest;
use App\Http\Requests\StoreordersRequest;
use App\Http\Requests\UpdateorderRequest;
use App\Http\Requests\UpdateordersRequest;
use Illuminate\Http\Request;

class ordersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $query = Order::query(); // Start with a query builder instance    

    if ($request->has('cari') && !empty($request->cari)) {
            $query->where('nama', 'like', '%' . $request->cari . '%');
        }

    if ($request->filled('start_date') || $request->filled('end_date')) {
        $start_date = $request->filled('start_date') ? $request->start_date : null;
        $end_date = $request->filled('end_date') ? $request->end_date : now()->toDateString();

        if (!$start_date) {
            $start_date = date(01-01-1999);
        }

        $query->whereBetween('tanggal_ambil', [$start_date, $end_date]);
    }

        if ($request->has('status') && !empty($request->status)) {
            $query->whereIn('status', $request->status);
        }

        if ($request->has('transaksi') && !empty($request->transaksi)) {
            $query->whereIn('transaksi', $request->transaksi);
        }

        // Order by 'transaksi' (put 'pending' first), then by 'tanggal_ambil'
        $query->orderByRaw("FIELD(status, 'pending') DESC")->orderBy('tanggal_ambil', 'ASC');

        // Paginate the query result
        
        $orders = $query->paginate(10); 
        return view('order', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('addorder');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required',
            'catatan' => 'required',
            'jam_ambil' => 'required',
            'tanggal_ambil' => 'required'

        ]);
        

          try {
            Order::create([
                'nama' => $request->nama,
                'harga' => $request->harga,
                'catatan' => $request->catatan,
                'jam_ambil' => $request->jam_ambil,
                'tanggal_ambil' => $request->tanggal_ambil,
                'tanggal_income' => $request->tanggal_ambil,
            ]);

            return redirect()->route('addorder')->with('success', 'Data Sudah Masuk');

        } catch (\Exception $e) {
            return redirect()->route('addorder')->with('error', 'Data Gagal Masuk');
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        if (!$order) {
            return redirect()->route('orders')->with('error', 'Order tidak ditemukan');
        }
        return view('DetailOrder', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('editOrder', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'harga' => 'required',
            'catatan' => 'required',
            'jam_ambil' => 'required',
            'tanggal_ambil' => 'required',
            'status' => 'pending'
        ]);

        $order = Order::findOrFail($id);
        try {
            $validated['tanggal_income'] = $validated['tanggal_ambil'];            
            $order->update($validated);
            return redirect()->route('orders')->with('success', 'Order telah diupdate');
        } catch (\Exception $e) {
            return redirect()->route('orders')->with('error', 'Order gagal update');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        try {
            $order->delete();
            return redirect()->route('orders')->with('success', 'Order telah dihapus');
        } catch (\Exception $e) {
            return redirect()->route('orders')->with('error', 'Order gagal dihapus');
        }
        

        
    }

    public function getOrdersForCalendar()
    {
        $orders = Order::select('id', 'nama', 'tanggal_ambil as start')
            ->get();

        return response()->json($orders);
    }

}

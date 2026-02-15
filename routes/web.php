<?php

use App\Http\Controllers\OrdersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenerateReport;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\OutcomeController;
use App\Http\Controllers\SummaryController;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Outcome;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard',[SummaryController::class,'dashboard'])->name('dashboard');

    //ORDERS
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders');
    Route::get('/orders/{id}', [OrdersController::class, 'show'])->name('orders.show');
    Route::get('/addorder', [OrdersController::class, 'create'])->name('addorder');    
    Route::post('/orders/tambah', [OrdersController::class, 'store'])->name('orders.tambah');
    Route::get('/orders/{id}/edit', [OrdersController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{id}', [OrdersController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{id}', [OrdersController::class, 'destroy'])->name('orders.destroy');

    //CashFlow
    Route::get('/income', [IncomeController::class, 'index'])->name('income');
    Route::post('/addincome/{id}/payment', [IncomeController::class, 'create'])->name('addincome');
    Route::get('/income/{tanggal_income}/edit', [IncomeController::class, 'edit'])->name('income.edit');
    Route::put('/income/{tanggal_income}', [IncomeController::class, 'update'])->name('income.update');
    Route::delete('/income/{tanggal_income}', [IncomeController::class, 'destroy'])->name('income.destroy');

    //OUTCOME
    Route::get('/outcome', [OutcomeController::class, 'index'])->name('outcome');
    Route::get('/addoutcome', [OutcomeController::class, 'create'])->name('addoutcome');
    Route::post('/outcome/tambah', [OutcomeController::class, 'store'])->name('outcome.tambah');
    Route::get('/outcome/{id}/edit', [OutcomeController::class, 'edit'])->name('outcome.edit');
    Route::put('/outcome/{id}', [OutcomeController::class, 'update'])->name('outcome.update');
    Route::delete('/outcome/{id}', [OutcomeController::class, 'destroy'])->name('outcome.destroy');

    //REPORT
    Route::post('/export-orders', [GenerateReport::class,'exportOrders'])->name('export_orders');
    Route::post('/export-outcomes', [GenerateReport::class,'exportOutcomes'])->name('export_outcomes');
    Route::post('/export-incomes', [GenerateReport::class,'exportCashflow'])->name('export_incomes');
    Route::post('/export-summary', [GenerateReport::class,'exportSummary'])->name('export_summary');
    

    //SUMMARY
    Route::get('/summary/store', [SummaryController::class, 'store'])->name('summary.store');
    Route::get('/summary', [SummaryController::class, 'index'])->name('summary');
    Route::get('/summary/{id}/edit', [SummaryController::class, 'edit'])->name('summary.edit');
    Route::put('/summary/{id}', [SummaryController::class, 'update'])->name('summary.update');
    Route::delete('/summary/{id}', [SummaryController::class, 'destroy'])->name('summary.destroy');

    //CALENDAR
    Route::get('/api/orders', function (Request $request) {
        $year = $request->query('year');
        $month = $request->query('month');

        $orders = Order::whereYear('tanggal_ambil', $year)
                    ->whereMonth('tanggal_ambil', $month)
                    ->where('status', '!=', 'done')
                    ->get();

        return response()->json($orders);
    });



});

// login


Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Outcome;
use App\Models\Summary;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class GenerateReport extends Controller
{
    public function exportOrders(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $headers = ['No', 'Nama', 'Harga', 'Catatan', 'Jam Ambil', 'Tanggal Ambil', 'Status', 'Transaksi'];
        $sheet->fromArray([$headers], null, 'A1');

        // Mendapatkan input bulan dan tahun
        $bulanTahun = $request->input('month');
        $bulan = Carbon::parse($bulanTahun)->month;
        $tahun = Carbon::parse($bulanTahun)->year;

        // Membuat objek Carbon dengan bulan dan tahun yang diinginkan
        $tanggalMulai = Carbon::create($tahun, $bulan, 1);
        $tanggalAkhir = $tanggalMulai->copy()->endOfMonth();

        // Mengambil data berdasarkan rentang tanggal
        $orders = Order::whereBetween('tanggal_income', [$tanggalMulai, $tanggalAkhir])->get();
        $row = 2; // Mulai dari baris ke-2 (karena baris pertama adalah header)
        foreach ($orders as $index => $order) {
            $sheet->fromArray([
                $index + 1,
                $order->nama,
                $order->harga,
                $order->catatan,
                $order->jam_ambil,
                $order->tanggal_ambil,
                $order->status,
                $order->transaksi,
            ], null, 'A' . $row);
            $row++;
        }

        // Styling (opsional)
        $sheet->getStyle('A1:H1')->getFont()->setBold(true); // Buat header tebal
        $sheet->getColumnDimension('A')->setWidth(5);  // No
        $sheet->getColumnDimension('B')->setWidth(20); // Nama
        $sheet->getColumnDimension('C')->setWidth(20); // Harga
        $sheet->getColumnDimension('D')->setWidth(30); // Catatan
        $sheet->getColumnDimension('E')->setWidth(15); // Jam Ambil
        $sheet->getColumnDimension('F')->setWidth(15); // Tanggal Ambil
        $sheet->getColumnDimension('G')->setWidth(10); // Status
        $sheet->getColumnDimension('H')->setWidth(15); // Transaksi

         $sheet->getStyle('C')->getNumberFormat()
      ->setFormatCode('[$Rp-421] #,##0.00;[Red]-[$Rp-421] #,##0.00');

        // Stream file Excel ke browser
        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $filename = 'Data_Pesanan' . date('Ymd') . '.xlsx';

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function exportOutcomes(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $headers = ['No', 'Total', 'Keterangan', 'Tanggal'];
        $sheet->fromArray([$headers], null, 'A1');

        // Ambil data dari database dan masukkan ke Excel                
        $monthYear = $request->input('month');

        // Format the monthYear to always have the first day of the month
        $monthYear = Carbon::createFromFormat('Y-m', $monthYear)->format('Y-m-01');

        // Retrieve all daily income records for the given month-year
        $orders = Outcome::where('month_year', $monthYear)->get();        
        $row = 2; // Mulai dari baris ke-2 (karena baris pertama adalah header)
        foreach ($orders as $index => $order) {
            $sheet->fromArray([
                $index + 1,
                $order->total,
                $order->keterangan,
                $order->tanggal,

            ], null, 'A' . $row);
            $row++;
        }

        // Styling (opsional)
        $sheet->getStyle('A1:H1')->getFont()->setBold(true); // Buat header tebal
        $sheet->getColumnDimension('A')->setWidth(5);  // No
        $sheet->getColumnDimension('B')->setWidth(20); // Nama
        $sheet->getColumnDimension('C')->setWidth(30); // Harga
        $sheet->getColumnDimension('D')->setWidth(30); // Catatan
        $sheet->getColumnDimension('E')->setWidth(15); // Jam Ambil
        $sheet->getColumnDimension('F')->setWidth(15); // Tanggal Ambil
        $sheet->getColumnDimension('G')->setWidth(10); // Status
        $sheet->getColumnDimension('H')->setWidth(15); // Transaksi

         $sheet->getStyle('B')->getNumberFormat()
      ->setFormatCode('[$Rp-421] #,##0.00;[Red]-[$Rp-421] #,##0.00');

        // Stream file Excel ke browser
        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $filename = 'Data_Pengeluaran' . date('Ymd') . '.xlsx';

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function exportCashflow(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $headers = ['No', 'Gopay', 'Cash', 'Bsi', 'Total', 'Total Outcome','Profit', 'Tanggal'];
        $sheet->fromArray([$headers], null, 'A1');


        // Ambil data dari database dan masukkan ke Excel
        // Get the month and year input
        $monthYear = $request->input('month');

        // Format the monthYear to always have the first day of the month
        $monthYear = Carbon::createFromFormat('Y-m', $monthYear)->format('Y-m-01');

        // Retrieve all daily income records for the given month-year
        $orders = Income::where('month_year', $monthYear)->get();
        $row = 2; // Mulai dari baris ke-2 (karena baris pertama adalah header)
        foreach ($orders as $index => $order) {
            $profit = $order->total - $order->total_outcome;
            $sheet->fromArray([
                $index + 1,
                $order->gopay,
                $order->cash,
                $order->bsi,
                $order->total,
                $order->total_outcome,
                $profit,   
                $order->tanggal_income,
            ], null, 'A' . $row);
            $row++;
        }

        // Styling (opsional)
        $sheet->getStyle('A1:H1')->getFont()->setBold(true); // Buat header tebal
        $sheet->getColumnDimension('A')->setWidth(5);  // No
        $sheet->getColumnDimension('B')->setWidth(20); // Nama
        $sheet->getColumnDimension('C')->setWidth(20); // Harga
        $sheet->getColumnDimension('D')->setWidth(20); // Catatan
        $sheet->getColumnDimension('E')->setWidth(30); // Jam Ambil
        $sheet->getColumnDimension('F')->setWidth(30); // Tanggal Ambil
        $sheet->getColumnDimension('G')->setWidth(30); // Status
        $sheet->getColumnDimension('H')->setWidth(15); // Transaksi
        $sheet->getColumnDimension('H')->setWidth(15); // Transaksi

        $sheet->getStyle('B:H')->getNumberFormat()
      ->setFormatCode('[$Rp-421] #,##0.00;[Red]-[$Rp-421] #,##0.00');

        // Stream file Excel ke browser
        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $filename = 'Data_cashFlow' . date('Ymd') . '.xlsx';

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
    public function exportSummary(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $headers = ['No', 'Tanggal', 'Total Income', 'Total Outcome', 'Total'];
        $sheet->fromArray([$headers], null, 'A1');

        // Ambil data dari database dan masukkan ke Excel                
        $Year = $request->input('year');

        // Format the monthYear to always have the first day of the month
        // $monthYear = Carbon::createFromFormat('Y-m', $monthYear)->format('Y-m-01');

        // Retrieve all daily income records for the given month-year
        // $orders = Summary::where('month_year', $monthYear)->get(); 
        $orders = Summary::whereYear('month_year', $Year)->get();       
        $row = 2; // Mulai dari baris ke-2 (karena baris pertama adalah header)
        foreach ($orders as $index => $order) {
            $sheet->fromArray([
                $index + 1,
                $order->month_year,
                $order->total_income,
                $order->total_outcome,
                $order->total,                

            ], null, 'A' . $row);
            $row++;
        }

        // Styling (opsional)
        $sheet->getStyle('A1:H1')->getFont()->setBold(true); // Buat header tebal
        $sheet->getColumnDimension('A')->setWidth(5);  // No
        $sheet->getColumnDimension('B')->setWidth(20); // Nama
        $sheet->getColumnDimension('C')->setWidth(20); // Harga
        $sheet->getColumnDimension('D')->setWidth(20); // Catatan
        $sheet->getColumnDimension('E')->setWidth(30); // Jam Ambil
        $sheet->getColumnDimension('F')->setWidth(15); // Tanggal Ambil
        $sheet->getColumnDimension('G')->setWidth(10); // Status
        $sheet->getColumnDimension('H')->setWidth(15); // Transaksi

         $sheet->getStyle('C:F')->getNumberFormat()
      ->setFormatCode('[$Rp-421] #,##0.00;[Red]-[$Rp-421] #,##0.00');

        // Stream file Excel ke browser
        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $filename = 'Data_Summary' . date('Ymd') . '.xlsx';

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}

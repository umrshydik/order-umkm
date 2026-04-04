<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Carbon\Carbon;

class IncomeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view pendapatan', only: ['index', 'export']),
        ];
    }

    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $incomes = Order::where('status', 'completed')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) as date, COUNT(id) as total_orders, SUM(total_price) as total_revenue')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('admin.incomes.index', compact('incomes', 'startDate', 'endDate'));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $incomes = Order::where('status', 'completed')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) as date, COUNT(id) as total_orders, SUM(total_price) as total_revenue')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $filename = "Laporan_Pendapatan_{$startDate}_sampai_{$endDate}.csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($incomes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'Total Order Selesai', 'Total Pendapatan (Rp)']);

            $totalSemua = 0;
            foreach ($incomes as $row) {
                fputcsv($file, [
                    $row->date,
                    $row->total_orders,
                    $row->total_revenue
                ]);
                $totalSemua += $row->total_revenue;
            }
            fputcsv($file, ['', 'TOTAL KESELURUHAN', $totalSemua]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

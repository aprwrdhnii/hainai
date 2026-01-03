<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Invoice;
use App\Models\Sparepart;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function services(Request $request)
    {
        $period = $request->period ?? 'daily';
        
        if ($period == 'daily') {
            $startDate = $request->date ?? now()->format('Y-m-d');
            $endDate = $startDate;
        } elseif ($period == 'monthly') {
            $month = $request->month ?? now()->format('Y-m');
            $startDate = $month . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
        } else {
            $year = $request->year ?? now()->format('Y');
            $startDate = $year . '-01-01';
            $endDate = $year . '-12-31';
        }

        $services = Service::with(['vehicle.customer', 'mechanic'])
            ->whereBetween('service_date', [$startDate, $endDate])
            ->orderBy('service_date', 'desc')
            ->get();

        $summary = [
            'total' => $services->count(),
            'lunas' => $services->where('status', 'lunas')->count(),
            'bon' => $services->where('status', 'bon')->count(),
            'pendapatan' => $services->where('status', 'lunas')->sum('total'),
            'total_bon' => $services->where('status', 'bon')->sum('total'),
        ];

        return view('reports.services', compact('services', 'summary', 'startDate', 'endDate', 'period'));
    }

    public function revenue(Request $request)
    {
        $period = $request->period ?? 'daily';
        
        if ($period == 'daily') {
            $startDate = $request->date ?? now()->format('Y-m-d');
            $endDate = $startDate;
        } elseif ($period == 'monthly') {
            $month = $request->month ?? now()->format('Y-m');
            $startDate = $month . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
        } else {
            $year = $request->year ?? now()->format('Y');
            $startDate = $year . '-01-01';
            $endDate = $year . '-12-31';
        }

        $services = Service::with(['vehicle.customer'])
            ->whereBetween('service_date', [$startDate, $endDate])
            ->orderBy('service_date', 'desc')
            ->get();

        $summary = [
            'total_service' => $services->count(),
            'pendapatan' => $services->where('status', 'lunas')->sum('total'),
            'total_bon' => $services->where('status', 'bon')->sum('total'),
            'lunas_count' => $services->where('status', 'lunas')->count(),
            'bon_count' => $services->where('status', 'bon')->count(),
        ];

        return view('reports.revenue', compact('services', 'summary', 'startDate', 'endDate', 'period'));
    }

    public function inventory()
    {
        $spareparts = Sparepart::orderBy('name')->get();

        $lowStock = $spareparts->filter(function($sp) {
            return $sp->stock <= $sp->min_stock;
        });

        $summary = [
            'total_items' => $spareparts->count(),
            'total_stock_value' => $spareparts->sum(function($sp) {
                return $sp->stock * $sp->buy_price;
            }),
            'low_stock_count' => $lowStock->count(),
        ];

        return view('reports.inventory', compact('spareparts', 'lowStock', 'summary'));
    }

    public function mechanics(Request $request)
    {
        $period = $request->period ?? 'monthly';
        
        if ($period == 'daily') {
            $startDate = $request->date ?? now()->format('Y-m-d');
            $endDate = $startDate;
        } elseif ($period == 'monthly') {
            $month = $request->month ?? now()->format('Y-m');
            $startDate = $month . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
        } else {
            $year = $request->year ?? now()->format('Y');
            $startDate = $year . '-01-01';
            $endDate = $year . '-12-31';
        }

        $mechanics = DB::table('mechanics')
            ->leftJoin('services', function($join) use ($startDate, $endDate) {
                $join->on('mechanics.id', '=', 'services.mechanic_id')
                    ->whereBetween('services.service_date', [$startDate, $endDate]);
            })
            ->select(
                'mechanics.*',
                DB::raw('COUNT(services.id) as total_services'),
                DB::raw('SUM(CASE WHEN services.status = "lunas" THEN 1 ELSE 0 END) as lunas_services'),
                DB::raw('SUM(CASE WHEN services.status = "bon" THEN 1 ELSE 0 END) as bon_services'),
                DB::raw('SUM(services.labor_cost) as total_labor'),
                DB::raw('SUM(CASE WHEN services.status = "lunas" THEN services.total ELSE 0 END) as total_pendapatan'),
                DB::raw('SUM(CASE WHEN services.status = "bon" THEN services.total ELSE 0 END) as total_bon'),
                DB::raw('AVG(CASE WHEN services.id IS NOT NULL THEN services.total ELSE NULL END) as avg_service')
            )
            ->groupBy('mechanics.id', 'mechanics.name', 'mechanics.phone', 'mechanics.specialization', 'mechanics.status', 'mechanics.created_at', 'mechanics.updated_at')
            ->orderByDesc('total_services')
            ->get();

        // Calculate ranking
        $rank = 1;
        foreach ($mechanics as $m) {
            $m->rank = $m->total_services > 0 ? $rank++ : '-';
            $m->completion_rate = $m->total_services > 0 
                ? round(($m->lunas_services / $m->total_services) * 100) 
                : 0;
        }

        // Summary
        $summary = [
            'total_services' => $mechanics->sum('total_services'),
            'total_lunas' => $mechanics->sum('lunas_services'),
            'total_bon' => $mechanics->sum('bon_services'),
            'total_pendapatan' => $mechanics->sum('total_pendapatan'),
            'total_bon_value' => $mechanics->sum('total_bon'),
        ];

        return view('reports.mechanics', compact('mechanics', 'startDate', 'endDate', 'period', 'summary'));
    }

    public function sendWhatsApp(Request $request)
    {
        $phone = $request->phone ?: config('services.whatsapp.boss_phone');

        if (empty($phone)) {
            return back()->with('error', 'Nomor WhatsApp belum diatur. Tambahkan WHATSAPP_BOSS_PHONE di file .env');
        }

        // Clean phone number
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Generate report message
        $wa = new WhatsAppService();
        $message = $wa->generateDailyReport();

        // Create wa.me link
        $waLink = 'https://wa.me/' . $phone . '?text=' . urlencode($message);

        // Redirect to WhatsApp Web
        return redirect()->away($waLink);
    }


    public function previewWhatsApp()
    {
        $wa = new WhatsAppService();
        $message = $wa->generateDailyReport();
        
        return response()->json([
            'message' => $message,
            'phone' => config('services.whatsapp.boss_phone'),
        ]);
    }
}

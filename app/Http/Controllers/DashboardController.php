<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Mechanic;
use App\Models\Sparepart;
use App\Models\Service;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'customers' => Customer::count(),
            'vehicles' => Vehicle::count(),
            'services_bon' => Service::where('status', 'bon')->count(),
            'total_bon' => Service::where('status', 'bon')->sum('total'),
            'services_lunas' => Service::where('status', 'lunas')->count(),
            'low_stock' => Sparepart::whereColumn('stock', '<=', 'min_stock')->count(),
            'revenue_today' => Service::whereDate('service_date', today())->where('status', 'lunas')->sum('total'),
            'revenue_month' => Service::whereMonth('service_date', now()->month)->where('status', 'lunas')->sum('total'),
        ];

        $recentServices = Service::with(['vehicle.customer', 'mechanic'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentServices'));
    }
}

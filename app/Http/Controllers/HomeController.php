<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\ServicePackage;
use App\Models\Sparepart;
use App\Models\Testimonial;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $promos = Promo::active()->latest()->take(5)->get();
        $testimonials = Testimonial::approved()->latest()->take(6)->get();
        $packages = ServicePackage::where('is_active', true)->get();
        
        return view('home.index', compact('promos', 'testimonials', 'packages'));
    }

    public function spareparts(Request $request)
    {
        $query = Sparepart::query();
        
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%');
        }
        
        if ($request->category) {
            $query->where('category', $request->category);
        }
        
        $spareparts = $query->orderBy('name')->paginate(12);
        $categories = Sparepart::distinct()->pluck('category')->filter();
        
        return view('home.spareparts', compact('spareparts', 'categories'));
    }

    public function estimasi()
    {
        $packages = ServicePackage::where('is_active', true)->get();
        $spareparts = Sparepart::where('stock', '>', 0)->orderBy('name')->get();
        
        return view('home.estimasi', compact('packages', 'spareparts'));
    }

    public function cekService(Request $request)
    {
        $service = null;
        $searched = false;
        
        if ($request->has('q')) {
            $searched = true;
            $q = $request->q;
            $service = Service::with(['vehicle.customer', 'mechanic', 'details.sparepart'])
                ->where('service_number', $q)
                ->orWhereHas('vehicle.customer', function ($query) use ($q) {
                    $query->where('phone', 'like', '%' . $q . '%');
                })
                ->latest()
                ->first();
        }
        
        return view('home.cek-service', compact('service', 'searched'));
    }

    public function submitTestimonial(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vehicle' => 'nullable|string|max:255',
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Testimonial::create($validated);

        return back()->with('success', 'Terima kasih! Testimoni Anda akan ditampilkan setelah disetujui.');
    }
}

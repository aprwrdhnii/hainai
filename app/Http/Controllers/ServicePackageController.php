<?php

namespace App\Http\Controllers;

use App\Models\ServicePackage;
use Illuminate\Http\Request;

class ServicePackageController extends Controller
{
    public function index()
    {
        $packages = ServicePackage::latest()->paginate(10);
        return view('service-packages.index', compact('packages'));
    }

    public function create()
    {
        return view('service-packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        ServicePackage::create($validated);

        return redirect()->route('service-packages.index')->with('success', 'Paket layanan berhasil ditambahkan');
    }

    public function edit(ServicePackage $servicePackage)
    {
        return view('service-packages.edit', compact('servicePackage'));
    }

    public function update(Request $request, ServicePackage $servicePackage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $servicePackage->update($validated);

        return redirect()->route('service-packages.index')->with('success', 'Paket layanan berhasil diperbarui');
    }

    public function destroy(ServicePackage $servicePackage)
    {
        $servicePackage->delete();
        return redirect()->route('service-packages.index')->with('success', 'Paket layanan berhasil dihapus');
    }
}

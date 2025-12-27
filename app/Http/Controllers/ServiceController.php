<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Mechanic;
use App\Models\Sparepart;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with(['vehicle.customer', 'mechanic']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->mechanic_id) {
            $query->where('mechanic_id', $request->mechanic_id);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('service_date', [$request->start_date, $request->end_date]);
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $mechanic = $request->mechanic_id ? Mechanic::find($request->mechanic_id) : null;
        
        return view('services.index', compact('services', 'mechanic'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $vehicles = Vehicle::with('customer')->orderBy('name')->get();
        $mechanics = Mechanic::where('status', 'active')->orderBy('name')->get();
        $spareparts = Sparepart::where('stock', '>', 0)->orderBy('name')->get();
        return view('services.create', compact('customers', 'vehicles', 'mechanics', 'spareparts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_type' => 'required|in:existing,new',
            'vehicle_type' => 'required|in:existing,new',
            'mechanic_id' => 'nullable|exists:mechanics,id',
            'service_date' => 'required|date',
            'complaint' => 'nullable|string',
            'labor_cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:bon,lunas',
            'spareparts' => 'nullable|array',
            'spareparts.*.id' => 'required|exists:spareparts,id',
            'spareparts.*.qty' => 'required|integer|min:1',
        ]);

        // Validation based on type
        if ($request->customer_type === 'existing') {
            $request->validate(['customer_id' => 'required|exists:customers,id']);
        } else {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_phone' => 'nullable|string|max:20',
            ]);
        }

        if ($request->vehicle_type === 'existing') {
            $request->validate(['vehicle_id' => 'required|exists:vehicles,id']);
        } else {
            $request->validate([
                'vehicle_name' => 'required|string|max:255',
            ]);
        }

        DB::beginTransaction();
        try {
            // Handle Customer
            if ($request->customer_type === 'new') {
                $customer = Customer::create([
                    'name' => $request->customer_name,
                    'phone' => $request->customer_phone,
                ]);
                $customerId = $customer->id;
            } else {
                $customerId = $request->customer_id;
            }

            // Handle Vehicle
            if ($request->vehicle_type === 'new') {
                $vehicle = Vehicle::create([
                    'customer_id' => $customerId,
                    'name' => $request->vehicle_name,
                ]);
                $vehicleId = $vehicle->id;
            } else {
                $vehicleId = $request->vehicle_id;
            }

            // Create Service
            $laborCost = $request->labor_cost ?? 0;
            $service = Service::create([
                'service_number' => Service::generateServiceNumber(),
                'vehicle_id' => $vehicleId,
                'mechanic_id' => $request->mechanic_id,
                'service_date' => $request->service_date,
                'service_time' => $request->service_time,
                'complaint' => $request->complaint,
                'status' => $request->status,
                'labor_cost' => $laborCost,
            ]);

            // Handle Spareparts
            $totalParts = 0;
            if ($request->has('spareparts') && is_array($request->spareparts)) {
                foreach ($request->spareparts as $item) {
                    $sparepart = Sparepart::find($item['id']);
                    if ($sparepart && $sparepart->stock >= $item['qty']) {
                        $subtotal = $sparepart->sell_price * $item['qty'];
                        ServiceDetail::create([
                            'service_id' => $service->id,
                            'sparepart_id' => $sparepart->id,
                            'quantity' => $item['qty'],
                            'price' => $sparepart->sell_price,
                            'subtotal' => $subtotal,
                        ]);
                        $sparepart->decrement('stock', $item['qty']);
                        $totalParts += $subtotal;
                    }
                }
            }

            // Update totals
            $service->update([
                'total_parts' => $totalParts,
                'total' => $totalParts + $laborCost,
            ]);

            DB::commit();

            // Create notification
            $customerName = $request->customer_type === 'new' ? $request->customer_name : Customer::find($customerId)->name;
            $statusText = $request->status === 'lunas' ? 'LUNAS' : 'BON';
            Notification::notify(
                'service',
                'Servis Hanyar',
                "{$customerName} - {$service->service_number} ({$statusText}) - Rp " . number_format($service->total, 0, ',', '.'),
                'bi-wrench',
                $request->status === 'lunas' ? 'green' : 'orange',
                route('services.show', $service)
            );

            return redirect()->route('services.show', $service)->with('success', 'Service Order berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat service: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Service $service)
    {
        $service->load(['vehicle.customer', 'mechanic', 'details.sparepart', 'invoice']);
        $spareparts = Sparepart::where('stock', '>', 0)->orderBy('name')->get();
        return view('services.show', compact('service', 'spareparts'));
    }

    public function edit(Service $service)
    {
        $vehicles = Vehicle::with('customer')->orderBy('name')->get();
        $mechanics = Mechanic::where('status', 'active')->orderBy('name')->get();
        return view('services.edit', compact('service', 'vehicles', 'mechanics'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'mechanic_id' => 'nullable|exists:mechanics,id',
            'service_date' => 'required|date',
            'service_time' => 'nullable',
            'complaint' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'status' => 'required|in:bon,lunas',
            'labor_cost' => 'nullable|numeric|min:0',
        ]);

        $service->update($validated);
        $this->recalculateTotal($service);

        return redirect()->route('services.show', $service)->with('success', 'Service Order berhasil diperbarui');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service Order berhasil dihapus');
    }

    public function addPart(Request $request, Service $service)
    {
        $validated = $request->validate([
            'sparepart_id' => 'required|exists:spareparts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $sparepart = Sparepart::findOrFail($validated['sparepart_id']);

        if ($sparepart->stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi');
        }

        ServiceDetail::create([
            'service_id' => $service->id,
            'sparepart_id' => $sparepart->id,
            'quantity' => $validated['quantity'],
            'price' => $sparepart->sell_price,
            'subtotal' => $sparepart->sell_price * $validated['quantity'],
        ]);

        $sparepart->decrement('stock', $validated['quantity']);
        $this->recalculateTotal($service);

        return back()->with('success', 'Sparepart berhasil ditambahkan');
    }

    public function removePart(Service $service, ServiceDetail $detail)
    {
        $detail->sparepart->increment('stock', $detail->quantity);
        $detail->delete();
        $this->recalculateTotal($service);

        return back()->with('success', 'Sparepart berhasil dihapus');
    }

    private function recalculateTotal(Service $service)
    {
        $totalParts = $service->details()->sum('subtotal');
        $service->update([
            'total_parts' => $totalParts,
            'total' => $totalParts + $service->labor_cost,
        ]);
    }

    public function getVehiclesByCustomer(Customer $customer)
    {
        return response()->json($customer->vehicles);
    }

    public function updateStatus(Request $request, Service $service)
    {
        $request->validate([
            'status' => 'required|in:bon,lunas',
        ]);

        $service->update(['status' => $request->status]);

        // Optional: Send notification if needed, or just redirect back
        return back()->with('success', 'Status servis berhasil diperbarui');
    }
}

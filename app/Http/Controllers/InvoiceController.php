<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with(['vehicle.customer', 'mechanic', 'details.sparepart']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $services = $query->orderBy('service_date', 'desc')->paginate(15);
        
        $totalLunas = Service::where('status', 'lunas')->sum('total');
        $totalBon = Service::where('status', 'bon')->sum('total');
        
        return view('invoices.index', compact('services', 'totalLunas', 'totalBon'));
    }

    public function create(Service $service)
    {
        if ($service->invoice) {
            return redirect()->route('invoices.show', $service->invoice)->with('info', 'Invoice sudah ada');
        }

        return view('invoices.create', compact('service'));
    }

    public function store(Request $request, Service $service)
    {
        $validated = $request->validate([
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ]);

        $subtotal = $service->total;
        $discount = $validated['discount'] ?? 0;
        $tax = $validated['tax'] ?? 0;
        $total = $subtotal - $discount + $tax;

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'service_id' => $service->id,
            'invoice_date' => now(),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'payment_status' => 'unpaid',
        ]);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice berhasil dibuat');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('service.vehicle.customer', 'service.details.sparepart', 'service.mechanic');
        return view('invoices.show', compact('invoice'));
    }

    public function pay(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|max:50',
        ]);

        $invoice->update([
            'payment_status' => 'paid',
            'payment_method' => $validated['payment_method'],
        ]);

        $invoice->service->update(['status' => 'lunas']);

        return back()->with('success', 'Pembayaran berhasil dicatat');
    }

    public function print(Service $service)
    {
        $service->load('vehicle.customer', 'details.sparepart', 'mechanic');
        return view('invoices.print', compact('service'));
    }
}

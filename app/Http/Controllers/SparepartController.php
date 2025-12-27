<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SparepartController extends Controller
{
    public function index(Request $request)
    {
        $query = Sparepart::query();

        // Search by name or code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by stock
        if ($request->filled('stock')) {
            if ($request->stock === 'low') {
                $query->whereColumn('stock', '<=', 'min_stock');
            } elseif ($request->stock === 'available') {
                $query->whereColumn('stock', '>', 'min_stock');
            }
        }

        $spareparts = $query->orderBy('name')->paginate(20)->withQueryString();
        
        // Get unique categories for filter dropdown
        $categories = Sparepart::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('spareparts.index', compact('spareparts', 'categories'));
    }

    public function create()
    {
        $categories = Sparepart::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('spareparts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:spareparts',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'stock' => 'required|integer|min:0',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
        ]);

        Sparepart::create($validated);
        return redirect()->route('spareparts.index')->with('success', 'Sparepart berhasil ditambahkan');
    }

    // Quick Add - minimal fields
    public function quickStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sell_price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        // Auto generate code
        $lastId = Sparepart::max('id') ?? 0;
        $code = 'SP' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        Sparepart::create([
            'code' => $code,
            'name' => $validated['name'],
            'sell_price' => $validated['sell_price'],
            'buy_price' => $validated['sell_price'] * 0.7, // Default 70% dari harga jual
            'stock' => $validated['stock'] ?? 0,
            'min_stock' => 5,
        ]);

        return back()->with('success', 'Sparepart "' . $validated['name'] . '" berhasil ditambahkan');
    }

    // Import from CSV/Excel
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();
        try {
            $imported = 0;
            $skipped = 0;
            $errors = [];

            if (in_array($extension, ['csv', 'txt'])) {
                // Handle CSV
                $handle = fopen($file->getPathname(), 'r');
                $header = fgetcsv($handle, 0, ','); // Skip header row
                
                $row = 1;
                while (($data = fgetcsv($handle, 0, ',')) !== false) {
                    $row++;
                    if (count($data) < 2) continue; // Skip empty rows

                    $name = trim($data[0] ?? '');
                    $sellPrice = (float) str_replace(['.', ','], ['', '.'], trim($data[1] ?? '0'));
                    $stock = (int) ($data[2] ?? 0);
                    $category = trim($data[3] ?? '');

                    if (empty($name)) {
                        $skipped++;
                        continue;
                    }

                    // Check if exists
                    if (Sparepart::where('name', $name)->exists()) {
                        $skipped++;
                        continue;
                    }

                    // Auto generate code
                    $lastId = Sparepart::max('id') ?? 0;
                    $code = 'SP' . str_pad($lastId + $imported + 1, 4, '0', STR_PAD_LEFT);

                    Sparepart::create([
                        'code' => $code,
                        'name' => $name,
                        'sell_price' => $sellPrice,
                        'buy_price' => $sellPrice * 0.7,
                        'stock' => $stock,
                        'min_stock' => 5,
                        'category' => $category ?: null,
                    ]);

                    $imported++;
                }
                fclose($handle);
            } else {
                // For Excel files, we'll need a simple approach
                return back()->with('error', 'Untuk file Excel, silakan convert ke CSV terlebih dahulu.');
            }

            DB::commit();
            
            $message = "Berhasil import {$imported} sparepart.";
            if ($skipped > 0) {
                $message .= " ({$skipped} data dilewati karena sudah ada atau kosong)";
            }
            
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    // Download template CSV
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_sparepart.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            // Header
            fputcsv($file, ['Nama', 'Harga Jual', 'Stok', 'Kategori']);
            // Contoh data
            fputcsv($file, ['Oli Mesin 1L', '55000', '50', 'Oli']);
            fputcsv($file, ['Filter Oli', '35000', '30', 'Filter']);
            fputcsv($file, ['Kampas Rem Depan', '85000', '20', 'Rem']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show(Sparepart $sparepart)
    {
        return view('spareparts.show', compact('sparepart'));
    }

    public function edit(Sparepart $sparepart)
    {
        $categories = Sparepart::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('spareparts.edit', compact('sparepart', 'categories'));
    }

    public function update(Request $request, Sparepart $sparepart)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:spareparts,code,' . $sparepart->id,
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'stock' => 'required|integer|min:0',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($sparepart->image && file_exists(public_path('uploads/spareparts/' . $sparepart->image))) {
                unlink(public_path('uploads/spareparts/' . $sparepart->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $sparepart->id . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/spareparts'), $imageName);
            $validated['image'] = $imageName;
        }

        $sparepart->update($validated);
        return redirect()->route('spareparts.index')->with('success', 'Onderdil sudah diubah');
    }

    public function destroy(Sparepart $sparepart)
    {
        $sparepart->delete();
        return redirect()->route('spareparts.index')->with('success', 'Sparepart berhasil dihapus');
    }

    public function addStock(Request $request, Sparepart $sparepart)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $sparepart->increment('stock', $validated['quantity']);
        return back()->with('success', 'Stok berhasil ditambahkan');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Notification;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::query();

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('expense_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('expense_date', '<=', $request->end_date);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(20)->withQueryString();
        
        // Get categories for filter
        $categories = Expense::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        // Calculate totals
        $totalThisMonth = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');

        $totalToday = Expense::whereDate('expense_date', today())->sum('amount');

        return view('expenses.index', compact('expenses', 'categories', 'totalThisMonth', 'totalToday'));
    }

    public function create()
    {
        $categories = Expense::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'category' => 'nullable|string|max:100',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        $expense = Expense::create($validated);

        // Create notification
        Notification::notify(
            'expense',
            'Pangeluaran Hanyar',
            $expense->description . ' - Rp ' . number_format($expense->amount, 0, ',', '.'),
            'bi-wallet2',
            'red',
            route('expenses.index')
        );

        return redirect()->route('expenses.index')->with('success', 'Pangeluaran sudah ditambah');
    }

    public function edit(Expense $expense)
    {
        $categories = Expense::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'category' => 'nullable|string|max:100',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Pangeluaran sudah diubah');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Pangeluaran sudah dibuang');
    }
}

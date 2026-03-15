<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BranchHelper;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;
        $branchId = BranchHelper::activeBranchId();

        $query = Expense::where('clinic_id', $clinic->id)
            ->with(['category', 'creator']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('category', fn($c) => $c->where('name_en', 'like', "%{$search}%")->orWhere('name_ar', 'like', "%{$search}%"));
            });
        }

        if ($categoryId = $request->get('category')) {
            $query->where('expense_category_id', $categoryId);
        }

        if ($method = $request->get('payment_method')) {
            $query->where('payment_method', $method);
        }

        // Date filter
        $from = $request->get('from');
        $to = $request->get('to');
        if ($from) {
            $query->where('expense_date', '>=', $from);
        }
        if ($to) {
            $query->where('expense_date', '<=', $to);
        }

        $expenses = $query->latest('expense_date')->paginate(15);

        // Stats
        $statsQuery = Expense::where('clinic_id', $clinic->id);
        if ($branchId) {
            $statsQuery->where('branch_id', $branchId);
        }
        $monthStart = now()->startOfMonth()->format('Y-m-d');
        $monthEnd = now()->endOfMonth()->format('Y-m-d');

        $stats = [
            'total' => (clone $statsQuery)->sum('amount'),
            'this_month' => (clone $statsQuery)->whereBetween('expense_date', [$monthStart, $monthEnd])->sum('amount'),
            'count' => (clone $statsQuery)->count(),
        ];

        $categories = ExpenseCategory::where('clinic_id', $clinic->id)->get();

        // Seed defaults if no categories exist
        if ($categories->isEmpty()) {
            ExpenseCategory::seedDefaults($clinic->id);
            $categories = ExpenseCategory::where('clinic_id', $clinic->id)->get();
        }

        return view('admin.expenses.index', compact('expenses', 'stats', 'categories'));
    }

    public function create()
    {
        $clinic = auth()->user()->clinic;
        $categories = ExpenseCategory::where('clinic_id', $clinic->id)->get();

        if ($categories->isEmpty()) {
            ExpenseCategory::seedDefaults($clinic->id);
            $categories = ExpenseCategory::where('clinic_id', $clinic->id)->get();
        }

        return view('admin.expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,instapay',
            'receipt' => 'nullable|image|max:2048',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('expense-receipts', 'public');
        }

        Expense::create([
            'clinic_id' => $clinic->id,
            'branch_id' => BranchHelper::activeBranchId(),
            'expense_category_id' => $validated['expense_category_id'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'expense_date' => $validated['expense_date'],
            'payment_method' => $validated['payment_method'],
            'receipt_path' => $receiptPath,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('dashboard.expenses.index')
            ->with('success', __('app.expense_added'));
    }

    public function edit(Expense $expense)
    {
        $clinic = auth()->user()->clinic;
        abort_if($expense->clinic_id !== $clinic->id, 403);

        $categories = ExpenseCategory::where('clinic_id', $clinic->id)->get();

        return view('admin.expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $clinic = auth()->user()->clinic;
        abort_if($expense->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:500',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:cash,card,bank_transfer,instapay',
            'receipt' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('receipt')) {
            if ($expense->receipt_path) {
                Storage::disk('public')->delete($expense->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt')->store('expense-receipts', 'public');
        }

        $expense->update([
            'expense_category_id' => $validated['expense_category_id'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'expense_date' => $validated['expense_date'],
            'payment_method' => $validated['payment_method'],
            'receipt_path' => $validated['receipt_path'] ?? $expense->receipt_path,
        ]);

        return redirect()->route('dashboard.expenses.index')
            ->with('success', __('app.expense_updated'));
    }

    public function destroy(Expense $expense)
    {
        $clinic = auth()->user()->clinic;
        abort_if($expense->clinic_id !== $clinic->id, 403);

        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        return back()->with('success', __('app.expense_deleted'));
    }

    // === Category Management ===
    public function storeCategory(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
        ]);

        ExpenseCategory::create([
            'clinic_id' => $clinic->id,
            'name_en' => $validated['name_en'],
            'name_ar' => $validated['name_ar'],
            'color' => $validated['color'] ?? '#6366f1',
        ]);

        return back()->with('success', __('app.category_added'));
    }

    public function destroyCategory(ExpenseCategory $category)
    {
        $clinic = auth()->user()->clinic;
        abort_if($category->clinic_id !== $clinic->id, 403);

        if ($category->expenses()->exists()) {
            return back()->with('error', __('app.category_has_expenses'));
        }

        $category->delete();

        return back()->with('success', __('app.category_deleted'));
    }
}

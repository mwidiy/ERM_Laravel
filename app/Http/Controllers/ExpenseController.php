<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectExpense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectExpense::with(['project.client', 'user', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(15)->withQueryString();

        // Financial Metrics
        $totalApproved = ProjectExpense::whereIn('status', ['approved', 'reimbursed'])->sum('amount');
        $totalPending = ProjectExpense::where('status', 'submitted')->sum('amount');
        $totalBillable = ProjectExpense::whereIn('status', ['approved', 'reimbursed'])->where('is_billable_to_client', true)->sum('amount');
        $pendingCount = ProjectExpense::where('status', 'submitted')->count();

        $projects = Project::where('status', '!=', 'completed')->orderBy('name')->get();
        $users = User::where('status', 'active')->orderBy('name')->get();

        return view('expenses.index', compact(
            'expenses',
            'totalApproved',
            'totalPending',
            'totalBillable',
            'pendingCount',
            'projects',
            'users'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'category' => 'required|string',
            'title' => 'required|string|max:255',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:1000',
            'description' => 'nullable|string',
            'is_billable_to_client' => 'nullable|boolean',
            'receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('expense_receipts', 'public');
        }

        // Generate Expense Code: EXP-YYYYMM-XXXX
        $yearMonth = date('Ym');
        $count = ProjectExpense::where('expense_code', 'LIKE', "EXP-{$yearMonth}-%")->count() + 1;
        $expenseCode = sprintf("EXP-%s-%04d", $yearMonth, $count);

        ProjectExpense::create([
            'expense_code' => $expenseCode,
            'project_id' => $validated['project_id'],
            'user_id' => $validated['user_id'],
            'category' => $validated['category'],
            'title' => $validated['title'],
            'expense_date' => $validated['expense_date'],
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'is_billable_to_client' => $request->boolean('is_billable_to_client'),
            'receipt_path' => $receiptPath,
            'status' => 'submitted',
        ]);

        return redirect()->back()->with('success', "Beban pengeluaran {$expenseCode} berhasil diajukan untuk review PM / Finance.");
    }

    public function approve(Request $request, ProjectExpense $expense)
    {
        $approverId = auth()->id() ?? User::first()->id;

        $expense->update([
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', "Beban pengeluaran {$expense->expense_code} berhasil disetujui (Approved).");
    }

    public function reject(Request $request, ProjectExpense $expense)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $expense->update([
            'status' => 'rejected',
            'approved_by' => auth()->id() ?? User::first()->id,
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->back()->with('warning', "Beban pengeluaran {$expense->expense_code} ditolak.");
    }

    public function destroy(ProjectExpense $expense)
    {
        if ($expense->status === 'approved' || $expense->status === 'reimbursed') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus beban yang sudah disetujui.');
        }

        if ($expense->receipt_path) {
            Storage::disk('public')->delete($expense->receipt_path);
        }

        $expense->delete();

        return redirect()->back()->with('success', 'Beban pengeluaran berhasil dihapus.');
    }
}

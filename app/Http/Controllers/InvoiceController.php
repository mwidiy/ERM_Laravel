<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'project', 'payments']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('project', function ($pq) use ($search) {
                      $pq->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('company_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $invoices = $query->orderBy('issue_date', 'desc')->paginate(12)->withQueryString();

        // AR Metrics
        $totalOutstandingAR = Invoice::where('status', '!=', 'cancelled')->sum('remaining_balance');
        $totalOverdueAR = Invoice::where('status', 'overdue')->sum('remaining_balance');
        $totalCollected = Invoice::where('status', '!=', 'cancelled')->sum('paid_amount');
        $totalInvoicedAmount = Invoice::where('status', '!=', 'cancelled')->sum('total_amount');

        $clients = Client::orderBy('company_name')->get();

        return view('invoices.index', compact(
            'invoices',
            'totalOutstandingAR',
            'totalOverdueAR',
            'totalCollected',
            'totalInvoicedAmount',
            'clients'
        ));
    }

    public function create(Request $request)
    {
        $projects = Project::with(['client', 'milestones'])->where('status', '!=', 'cancelled')->orderBy('name')->get();
        $selectedProjectId = $request->query('project_id');
        $selectedMilestoneId = $request->query('milestone_id');

        $prefillProject = null;
        $prefillMilestone = null;

        if ($selectedProjectId) {
            $prefillProject = Project::with(['client', 'milestones'])->find($selectedProjectId);
        }

        if ($selectedMilestoneId) {
            $prefillMilestone = ProjectMilestone::find($selectedMilestoneId);
        }

        // Auto generate next invoice number: INV/YYYY/MM/XXXX
        $year = date('Y');
        $month = date('m');
        $count = Invoice::whereYear('issue_date', $year)->count() + 1;
        $suggestedInvoiceNumber = sprintf("INV/%s/%s/%04d", $year, $month, $count);

        return view('invoices.create', compact(
            'projects',
            'prefillProject',
            'prefillMilestone',
            'suggestedInvoiceNumber'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'project_id' => 'required|exists:projects,id',
            'milestone_id' => 'nullable|exists:project_milestones,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'withholding_tax_rate' => 'required|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string|max:100',
            'bank_details' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $project = Project::findOrFail($request->project_id);

        DB::beginTransaction();
        try {
            $creatorId = User::first()->id ?? null;

            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'project_id' => $project->id,
                'client_id' => $project->client_id,
                'milestone_id' => $request->milestone_id ?: null,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'tax_rate' => $request->tax_rate,
                'withholding_tax_rate' => $request->withholding_tax_rate,
                'discount_amount' => $request->discount_amount ?? 0,
                'status' => 'draft',
                'payment_terms' => $request->payment_terms ?? 'Net 30 Hari',
                'bank_details' => $request->bank_details ?? "Bank Mandiri Rekening: 137-00-1928374-1\na.n. PT Enterprise Solusi Indonesia\nCabang Jakarta Sudirman",
                'notes' => $request->notes,
                'created_by' => $creatorId,
            ]);

            foreach ($request->items as $item) {
                $qty = (float) $item['quantity'];
                $price = (float) $item['unit_price'];
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_description' => $item['description'],
                    'quantity' => $qty,
                    'unit' => $item['unit'],
                    'unit_price' => $price,
                    'total_price' => $qty * $price,
                ]);
            }

            // Hitung kalkulasi PPN & PPh 23
            $invoice->recalculateTotals();

            // Jika linked ke milestone, update status milestone
            if ($invoice->milestone_id) {
                $milestone = ProjectMilestone::find($invoice->milestone_id);
                if ($milestone && $milestone->status === 'planned') {
                    $milestone->update(['status' => 'in_progress']);
                }
            }

            DB::commit();

            return redirect()->route('invoices.show', $invoice->id)
                ->with('success', "Faktur penagihan {$invoice->invoice_number} berhasil diterbitkan dalam status Draft.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client.contacts', 'project.projectManager', 'milestone', 'items', 'payments.recorder']);
        
        return view('invoices.show', compact('invoice'));
    }

    public function markAsSent(Invoice $invoice)
    {
        if ($invoice->status === 'draft') {
            $invoice->update(['status' => 'sent']);
            return redirect()->back()->with('success', "Status faktur {$invoice->invoice_number} diubah menjadi 'Terkirim (Sent)'.");
        }

        return redirect()->back();
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['client.primaryContact', 'project.projectManager', 'milestone', 'items', 'payments']);
        
        return view('invoices.print', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->paid_amount > 0) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus faktur yang sudah memiliki riwayat pembayaran.');
        }

        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Faktur berhasil dihapus.');
    }
}

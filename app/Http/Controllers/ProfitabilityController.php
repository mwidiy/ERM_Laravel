<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\ProjectExpense;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProfitabilityController extends Controller
{
    /**
     * Dashboard Laba Rugi Proyek & Ringkasan Profitabilitas Eksekutif
     */
    public function index(Request $request)
    {
        $query = Project::with(['client', 'projectManager', 'timesheets', 'expenses', 'invoices']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $projects = $query->get()->map(function ($project) {
            $laborCost = $project->approvedLaborCost();
            $directExpense = $project->approvedDirectExpenses();
            $totalCost = $laborCost + $directExpense;
            $contractVal = (float) $project->contract_value;
            $invoiced = $project->totalInvoiced();
            $collected = $project->totalCollected();
            $ar = $project->outstandingAR();

            $grossProfit = $contractVal - $totalCost;
            $marginPct = $contractVal > 0 ? round(($grossProfit / $contractVal) * 100, 1) : 0;
            
            $budget = (float) $project->budget_cost;
            $budgetBurnPct = $budget > 0 ? round(($totalCost / $budget) * 100, 1) : 0;

            $costStatus = 'on_track';
            if ($totalCost > $budget && $budget > 0) {
                $costStatus = 'over_budget';
            } elseif ($budgetBurnPct >= 80) {
                $costStatus = 'near_limit';
            }

            return (object) [
                'model' => $project,
                'id' => $project->id,
                'code' => $project->project_code,
                'name' => $project->name,
                'client_name' => $project->client->company_name ?? 'N/A',
                'pm_name' => $project->projectManager->name ?? 'N/A',
                'contract_value' => $contractVal,
                'budget_cost' => $budget,
                'labor_cost' => $laborCost,
                'direct_expense' => $directExpense,
                'total_cost' => $totalCost,
                'invoiced' => $invoiced,
                'collected' => $collected,
                'outstanding_ar' => $ar,
                'gross_profit' => $grossProfit,
                'margin_percentage' => $marginPct,
                'budget_burn_percentage' => $budgetBurnPct,
                'cost_status' => $costStatus,
                'status' => $project->status,
                'progress_percentage' => $project->progress_percentage,
            ];
        });

        // Portfolio Totals
        $portfolioContractValue = $projects->sum('contract_value');
        $portfolioTotalCost = $projects->sum('total_cost');
        $portfolioGrossProfit = $portfolioContractValue - $portfolioTotalCost;
        $portfolioMarginPct = $portfolioContractValue > 0 ? round(($portfolioGrossProfit / $portfolioContractValue) * 100, 1) : 0;
        
        $portfolioLaborCost = $projects->sum('labor_cost');
        $portfolioDirectExpense = $projects->sum('direct_expense');
        $portfolioInvoiced = $projects->sum('invoiced');
        $portfolioCollected = $projects->sum('collected');
        $portfolioOutstandingAR = $projects->sum('outstanding_ar');

        $clients = Client::orderBy('company_name')->get();

        return view('profitability.index', compact(
            'projects',
            'portfolioContractValue',
            'portfolioTotalCost',
            'portfolioGrossProfit',
            'portfolioMarginPct',
            'portfolioLaborCost',
            'portfolioDirectExpense',
            'portfolioInvoiced',
            'portfolioCollected',
            'portfolioOutstandingAR',
            'clients'
        ));
    }

    /**
     * Analisis Laba Rugi Terperinci 1 Proyek
     */
    public function show(Project $project)
    {
        $project->load([
            'client.contacts',
            'projectManager',
            'members',
            'milestones.invoice',
            'timesheets.user',
            'expenses.user',
            'invoices.payments',
        ]);

        $laborCost = $project->approvedLaborCost();
        $directExpense = $project->approvedDirectExpenses();
        $totalCost = $laborCost + $directExpense;
        $contractVal = (float) $project->contract_value;
        $invoiced = $project->totalInvoiced();
        $collected = $project->totalCollected();
        $outstandingAR = $project->outstandingAR();

        $grossProfit = $contractVal - $totalCost;
        $marginPct = $contractVal > 0 ? round(($grossProfit / $contractVal) * 100, 1) : 0;

        // Breakdown Biaya Tenaga Kerja per Personil
        $laborByUser = $project->timesheets->where('status', 'approved')->groupBy('user_id')->map(function ($rows) {
            $user = $rows->first()->user;
            return (object) [
                'user_name' => $user->name ?? 'Unknown',
                'position' => $user->position ?? 'Consultant',
                'total_hours' => $rows->sum('hours'),
                'total_cost' => $rows->sum('total_cost'),
            ];
        });

        // Breakdown Beban Langsung per Kategori
        $expenseByCategory = $project->expenses->whereIn('status', ['approved', 'reimbursed'])->groupBy('category')->map(function ($rows, $cat) {
            return (object) [
                'category' => $cat,
                'category_label' => $rows->first()->category_label,
                'count' => $rows->count(),
                'total_amount' => $rows->sum('amount'),
            ];
        });

        return view('profitability.show', compact(
            'project',
            'laborCost',
            'directExpense',
            'totalCost',
            'contractVal',
            'invoiced',
            'collected',
            'outstandingAR',
            'grossProfit',
            'marginPct',
            'laborByUser',
            'expenseByCategory'
        ));
    }

    /**
     * Laporan Aging Piutang Usaha (AR Aging Schedule)
     */
    public function arAging(Request $request)
    {
        $now = Carbon::now()->startOfDay();

        $invoices = Invoice::with(['client', 'project'])
            ->where('status', '!=', 'cancelled')
            ->where('remaining_balance', '>', 0)
            ->get();

        $agingBuckets = [
            'current' => 0,     // Belum jatuh tempo
            '1_30' => 0,        // 1 - 30 hari overdue
            '31_60' => 0,       // 31 - 60 hari overdue
            '61_90' => 0,       // 61 - 90 hari overdue
            'over_90' => 0,     // > 90 hari overdue
        ];

        $clientAging = [];

        foreach ($invoices as $inv) {
            $dueDate = Carbon::parse($inv->due_date)->startOfDay();
            $daysOverdue = $now->diffInDays($dueDate, false); // negatif jika overdue
            $balance = (float) $inv->remaining_balance;

            $bucket = 'current';
            if ($daysOverdue < 0) {
                $absDays = abs($daysOverdue);
                if ($absDays <= 30) {
                    $bucket = '1_30';
                } elseif ($absDays <= 60) {
                    $bucket = '31_60';
                } elseif ($absDays <= 90) {
                    $bucket = '61_90';
                } else {
                    $bucket = 'over_90';
                }
            }

            $agingBuckets[$bucket] += $balance;

            // Group by Client
            $clientId = $inv->client_id;
            if (!isset($clientAging[$clientId])) {
                $clientAging[$clientId] = (object) [
                    'client' => $inv->client,
                    'total_ar' => 0,
                    'current' => 0,
                    '1_30' => 0,
                    '31_60' => 0,
                    '61_90' => 0,
                    'over_90' => 0,
                    'invoices' => collect(),
                ];
            }

            $clientAging[$clientId]->total_ar += $balance;
            $clientAging[$clientId]->{$bucket} += $balance;
            $clientAging[$clientId]->invoices->push((object)[
                'invoice' => $inv,
                'bucket' => $bucket,
                'days_diff' => $daysOverdue,
            ]);
        }

        $totalAR = array_sum($agingBuckets);

        return view('profitability.ar_aging', compact(
            'agingBuckets',
            'clientAging',
            'totalAR'
        ));
    }

    /**
     * Cetak Laporan Eksekutif Laba Rugi Proyek (A4 Board Report)
     */
    public function printReport(Request $request)
    {
        $projects = Project::with(['client', 'projectManager', 'timesheets', 'expenses', 'invoices'])->get();

        $portfolioContractValue = $projects->sum('contract_value');
        $portfolioLaborCost = $projects->sum(fn($p) => $p->approvedLaborCost());
        $portfolioDirectExpense = $projects->sum(fn($p) => $p->approvedDirectExpenses());
        $portfolioTotalCost = $portfolioLaborCost + $portfolioDirectExpense;
        $portfolioGrossProfit = $portfolioContractValue - $portfolioTotalCost;
        $portfolioMarginPct = $portfolioContractValue > 0 ? round(($portfolioGrossProfit / $portfolioContractValue) * 100, 1) : 0;

        return view('profitability.print', compact(
            'projects',
            'portfolioContractValue',
            'portfolioLaborCost',
            'portfolioDirectExpense',
            'portfolioTotalCost',
            'portfolioGrossProfit',
            'portfolioMarginPct'
        ));
    }
}

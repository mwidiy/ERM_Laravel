<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Invoice;
use App\Models\ProjectExpense;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalContractValue = Project::whereIn('status', ['planning', 'in_progress', 'completed'])->sum('contract_value');
        $activeProjectsCount = Project::whereIn('status', ['planning', 'in_progress'])->count();
        $totalClientsCount = Client::where('status', 'active')->count();
        $totalTeamMembers = User::where('status', 'active')->count();
        
        // Fase 3 AR & Invoicing Metrics
        $totalOutstandingAR = Invoice::where('status', '!=', 'cancelled')->sum('remaining_balance');
        $totalCollectedCash = Invoice::where('status', '!=', 'cancelled')->sum('paid_amount');
        $totalDirectExpenses = ProjectExpense::whereIn('status', ['approved', 'reimbursed'])->sum('amount');
        $totalLaborCost = Timesheet::where('status', 'approved')->sum('total_cost');

        $invoicedOrReadyMilestones = ProjectMilestone::whereIn('status', ['completed', 'invoiced'])->sum('billing_amount');
        
        $recentProjects = Project::with(['client', 'projectManager'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingMilestones = ProjectMilestone::with('project.client')
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderBy('due_date', 'asc')
            ->take(4)
            ->get();

        $recentInvoices = Invoice::with(['client', 'project'])
            ->latest('issue_date')
            ->take(4)
            ->get();

        $statusCounts = [
            'planning' => Project::where('status', 'planning')->count(),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'on_hold' => Project::where('status', 'on_hold')->count(),
        ];

        $user = auth()->user();
        $myTasks = $user ? \App\Models\ProjectTask::with('project')
            ->where('assigned_to', $user->id)
            ->whereIn('status', ['todo', 'in_progress', 'review'])
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get() : collect();

        $myHoursThisMonth = $user ? Timesheet::where('user_id', $user->id)
            ->whereMonth('entry_date', now()->month)
            ->sum('hours') : 0;

        return view('dashboard.index', compact(
            'totalContractValue',
            'activeProjectsCount',
            'totalClientsCount',
            'totalTeamMembers',
            'totalOutstandingAR',
            'totalCollectedCash',
            'totalDirectExpenses',
            'totalLaborCost',
            'invoicedOrReadyMilestones',
            'recentProjects',
            'upcomingMilestones',
            'recentInvoices',
            'statusCounts',
            'myTasks',
            'myHoursThisMonth'
        ));
    }
}

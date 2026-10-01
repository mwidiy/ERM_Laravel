<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectMilestone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');
        $priority = $request->input('priority');

        $query = Project::with(['client', 'projectManager', 'milestones']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('project_code', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($cq) use ($search) {
                      $cq->where('company_name', 'like', "%{$search}%");
                  });
            });
        }

        $projects = $query->latest()->paginate(9)->withQueryString();

        $stats = [
            'total_value' => Project::whereIn('status', ['planning', 'in_progress', 'completed'])->sum('contract_value'),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'planning' => Project::where('status', 'planning')->count(),
            'completed' => Project::where('status', 'completed')->count(),
        ];

        return view('projects.index', compact('projects', 'stats', 'status', 'search', 'priority'));
    }

    public function create()
    {
        $year = date('Y');
        $lastProject = Project::where('project_code', 'like', "PRJ-{$year}-%")->latest('id')->first();
        if ($lastProject) {
            $lastNum = (int) substr($lastProject->project_code, -3);
            $nextCode = sprintf("PRJ-%s-%03d", $year, $lastNum + 1);
        } else {
            $nextCode = sprintf("PRJ-%s-001", $year);
        }

        $clients = Client::where('status', 'active')->orderBy('company_name')->get();
        $projectManagers = User::whereIn('role', ['admin', 'project_manager'])->where('status', 'active')->get();

        return view('projects.create', compact('nextCode', 'clients', 'projectManagers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_code' => 'required|string|unique:projects,project_code',
            'client_id' => 'required|exists:clients,id',
            'project_manager_id' => 'nullable|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'contract_type' => 'required|in:fixed_fee,time_and_material,retainer',
            'contract_value' => 'required|numeric|min:0',
            'budget_cost' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
        ]);

        $project = DB::transaction(function () use ($validated) {
            $project = Project::create([
                'project_code' => $validated['project_code'],
                'client_id' => $validated['client_id'],
                'project_manager_id' => $validated['project_manager_id'] ?? null,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'contract_type' => $validated['contract_type'],
                'contract_value' => $validated['contract_value'],
                'budget_cost' => $validated['budget_cost'] ?? ($validated['contract_value'] * 0.5), // default budget 50%
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'] ?? null,
                'priority' => $validated['priority'],
                'status' => $validated['status'],
                'progress_percentage' => 0,
            ]);

            // Auto create DP Milestone jika fixed fee
            if ($project->contract_type === 'fixed_fee' && $project->contract_value > 0) {
                ProjectMilestone::create([
                    'project_id' => $project->id,
                    'title' => 'Termin 1: Uang Muka (Down Payment 30%)',
                    'description' => 'Pembayaran Uang Muka permulaan proyek dan kickoff meeting.',
                    'due_date' => $project->start_date,
                    'completion_percentage' => 0,
                    'billing_percentage' => 30,
                    'billing_amount' => $project->contract_value * 0.30,
                    'status' => 'pending',
                ]);
            }

            // Assign PM as first member
            if ($project->project_manager_id) {
                $pmUser = User::find($project->project_manager_id);
                ProjectMember::create([
                    'project_id' => $project->id,
                    'user_id' => $pmUser->id,
                    'project_role' => 'Project Delivery Manager',
                    'hourly_rate' => $pmUser->hourly_rate ?? 250000,
                    'assigned_date' => $project->start_date,
                ]);
            }

            return $project;
        });

        return redirect()->route('projects.show', $project)->with('success', "Proyek {$project->name} berhasil dibuat!");
    }

    public function show(Project $project)
    {
        $project->load([
            'client.contacts',
            'projectManager',
            'milestones.invoice',
            'members',
            'tasks.assignedUser',
            'tasks.milestone',
            'timesheets.user',
            'timesheets.approver',
            'timesheets.task',
            'expenses.user',
            'invoices.milestone',
        ]);

        $allUsers = User::where('status', 'active')
            ->whereNotIn('id', $project->members->pluck('id'))
            ->get();

        $invoicedAmount = $project->milestones->whereIn('status', ['completed', 'invoiced'])->sum('billing_amount');
        
        $actualLaborCost = $project->timesheets->where('status', 'approved')->sum('total_cost');
        $pendingLaborCost = $project->timesheets->where('status', 'submitted')->sum('total_cost');
        $totalLoggedHours = $project->timesheets->where('status', 'approved')->sum('hours');
        $remainingBudget = max(0, $project->budget_cost - $actualLaborCost);
        $budgetConsumedPercentage = $project->budget_cost > 0 ? min(100, ($actualLaborCost / $project->budget_cost) * 100) : 0;

        $estimatedMargin = $project->contract_value - $project->budget_cost;
        $marginPercentage = $project->contract_value > 0 ? ($estimatedMargin / $project->contract_value) * 100 : 0;

        return view('projects.show', compact(
            'project', 
            'allUsers', 
            'invoicedAmount', 
            'actualLaborCost', 
            'pendingLaborCost',
            'totalLoggedHours',
            'remainingBudget',
            'budgetConsumedPercentage',
            'estimatedMargin', 
            'marginPercentage'
        ));
    }

    public function edit(Project $project)
    {
        $clients = Client::where('status', 'active')->orderBy('company_name')->get();
        $projectManagers = User::whereIn('role', ['admin', 'project_manager'])->where('status', 'active')->get();

        return view('projects.edit', compact('project', 'clients', 'projectManagers'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'project_manager_id' => 'nullable|exists:users,id',
            'contract_type' => 'required|in:fixed_fee,time_and_material,retainer',
            'contract_value' => 'required|numeric|min:0',
            'budget_cost' => 'nullable|numeric|min:0',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('success', "Perubahan proyek {$project->name} berhasil disimpan!");
    }

    public function destroy(Project $project)
    {
        $name = $project->name;
        $project->delete();

        return redirect()->route('projects.index')->with('success', "Proyek {$name} berhasil dihapus.");
    }

    // Sub-actions: Add Milestone
    public function storeMilestone(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'completion_percentage' => 'required|integer|min:0|max:100',
            'billing_percentage' => 'required|numeric|min:0|max:100',
            'billing_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,in_progress,completed,invoiced',
        ]);

        $project->milestones()->create($validated);

        return redirect()->route('projects.show', ['project' => $project, 'tab' => 'milestones'])
            ->with('success', "Milestone '{$validated['title']}' berhasil ditambahkan ke jadwal proyek!");
    }

    // Sub-actions: Assign Member
    public function assignMember(Request $request, Project $project)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'project_role' => 'required|string|max:150',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        $user = User::find($validated['user_id']);
        $hourlyRate = $validated['hourly_rate'] ?? ($user->hourly_rate > 0 ? $user->hourly_rate : 200000);

        ProjectMember::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'project_role' => $validated['project_role'],
            'hourly_rate' => $hourlyRate,
            'assigned_date' => now()->toDateString(),
        ]);

        return redirect()->route('projects.show', ['project' => $project, 'tab' => 'team'])
            ->with('success', "{$user->name} berhasil ditugaskan sebagai {$validated['project_role']} pada proyek ini.");
    }

    // Sub-actions: Remove Member
    public function removeMember(Project $project, User $user)
    {
        ProjectMember::where('project_id', $project->id)->where('user_id', $user->id)->delete();

        return redirect()->route('projects.show', ['project' => $project, 'tab' => 'team'])
            ->with('success', "Personil {$user->name} dilepas dari alokasi tim proyek.");
    }
}

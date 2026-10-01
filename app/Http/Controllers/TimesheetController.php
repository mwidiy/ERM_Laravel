<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectTask;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->input('project_id');
        $userId = $request->input('user_id');
        $status = $request->input('status');

        $query = Timesheet::with(['project.client', 'user', 'task', 'approver']);

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $timesheets = $query->orderBy('entry_date', 'desc')->paginate(15)->withQueryString();

        $projects = Project::whereIn('status', ['planning', 'in_progress'])->with('tasks')->orderBy('name')->get();
        $team = User::where('status', 'active')->orderBy('name')->get();

        $stats = [
            'total_hours' => Timesheet::sum('hours'),
            'approved_hours' => Timesheet::where('status', 'approved')->sum('hours'),
            'billable_hours' => Timesheet::where('is_billable', true)->sum('hours'),
            'total_cost' => Timesheet::where('status', 'approved')->sum('total_cost'),
            'pending_count' => Timesheet::where('status', 'submitted')->count(),
        ];

        return view('timesheets.index', compact('timesheets', 'projects', 'team', 'stats', 'projectId', 'userId', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:project_tasks,id',
            'user_id' => 'required|exists:users,id',
            'entry_date' => 'required|date',
            'hours' => 'required|numeric|min:0.25|max:24',
            'description' => 'required|string|max:1000',
            'is_billable' => 'nullable|boolean',
        ]);

        $user = User::find($validated['user_id']);
        
        // Find hourly rate from project member assignment or user default
        $projectMember = ProjectMember::where('project_id', $validated['project_id'])
            ->where('user_id', $user->id)
            ->first();

        $hourlyRate = $projectMember && $projectMember->hourly_rate > 0 
            ? $projectMember->hourly_rate 
            : ($user->hourly_rate > 0 ? $user->hourly_rate : 200000);

        $hours = (float) $validated['hours'];
        $totalCost = $hours * $hourlyRate;

        $timesheet = Timesheet::create([
            'project_id' => $validated['project_id'],
            'task_id' => $validated['task_id'] ?? null,
            'user_id' => $user->id,
            'entry_date' => $validated['entry_date'],
            'hours' => $hours,
            'description' => $validated['description'],
            'is_billable' => $request->has('is_billable') ? (bool) $request->input('is_billable') : true,
            'hourly_rate' => $hourlyRate,
            'total_cost' => $totalCost,
            'status' => 'submitted',
        ]);

        // If task is attached, update actual_hours on task
        if (!empty($validated['task_id'])) {
            $task = ProjectTask::find($validated['task_id']);
            if ($task) {
                $task->increment('actual_hours', $hours);
            }
        }

        return redirect()->back()->with('success', "Log jam kerja ({$hours} jam) berhasil dicatat dan masuk antrian approval PM!");
    }

    public function approve(Request $request, Timesheet $timesheet)
    {
        $approverId = auth()->id() ?? ($timesheet->user_id);

        $timesheet->update([
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', "Timesheet #{$timesheet->id} ({$timesheet->hours} jam) berhasil disetujui & dicatat ke beban riil proyek!");
    }

    public function reject(Request $request, Timesheet $timesheet)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $timesheet->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return redirect()->back()->with('success', "Timesheet #{$timesheet->id} telah ditolak dengan catatan evaluasi.");
    }

    public function destroy(Timesheet $timesheet)
    {
        $timesheet->delete();

        return redirect()->back()->with('success', "Log timesheet berhasil dihapus.");
    }
}

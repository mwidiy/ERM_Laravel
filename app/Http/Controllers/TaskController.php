<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectTask;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->input('project_id');
        $status = $request->input('status');
        $priority = $request->input('priority');
        $assignedTo = $request->input('assigned_to');

        $query = ProjectTask::with(['project.client', 'assignedUser', 'milestone']);

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        if ($assignedTo) {
            $query->where('assigned_to', $assignedTo);
        }

        $tasks = $query->orderBy('due_date', 'asc')->paginate(15)->withQueryString();

        $projects = Project::whereIn('status', ['planning', 'in_progress'])->orderBy('name')->get();
        $team = User::where('status', 'active')->orderBy('name')->get();

        $stats = [
            'total' => ProjectTask::count(),
            'todo' => ProjectTask::where('status', 'todo')->count(),
            'in_progress' => ProjectTask::where('status', 'in_progress')->count(),
            'review' => ProjectTask::where('status', 'review')->count(),
            'done' => ProjectTask::where('status', 'done')->count(),
        ];

        return view('tasks.index', compact('tasks', 'projects', 'team', 'stats', 'projectId', 'status', 'priority', 'assignedTo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'milestone_id' => 'nullable|exists:project_milestones,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimated_hours' => 'nullable|numeric|min:0',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:todo,in_progress,review,done',
            'due_date' => 'nullable|date',
        ]);

        $task = ProjectTask::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'task' => $task]);
        }

        return redirect()->back()->with('success', "Tugas '{$task->title}' berhasil ditambahkan ke WBS proyek!");
    }

    public function updateStatus(Request $request, ProjectTask $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
            'actual_hours' => 'nullable|numeric|min:0',
        ]);

        $task->update($validated);

        return redirect()->back()->with('success', "Status tugas '{$task->title}' diperbarui menjadi " . strtoupper($task->status));
    }

    public function destroy(ProjectTask $task)
    {
        $title = $task->title;
        $task->delete();

        return redirect()->back()->with('success', "Tugas '{$title}' berhasil dihapus.");
    }
}

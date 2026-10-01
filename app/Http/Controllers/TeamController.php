<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
{
    public function index()
    {
        $team = User::withCount(['managedProjects', 'projects'])->latest()->get();

        $stats = [
            'total' => $team->count(),
            'pm' => $team->where('role', 'project_manager')->count(),
            'consultants' => $team->where('role', 'consultant')->count(),
            'finance' => $team->where('role', 'finance')->count(),
        ];

        return view('team.index', compact('team', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'role' => 'required|in:admin,project_manager,consultant,finance',
            'position' => 'required|string|max:150',
            'hourly_rate' => 'required|numeric|min:0',
        ]);

        $validated['password'] = Hash::make('password'); // Default initial password
        $validated['status'] = 'active';

        User::create($validated);

        return redirect()->route('team.index')->with('success', "Personil {$request->name} berhasil didaftarkan ke sistem ERP!");
    }
}

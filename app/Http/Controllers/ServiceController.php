<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->get();
        $categories = Service::distinct()->pluck('category');

        return view('services.index', compact('services', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_code' => 'required|string|unique:services,service_code',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:150',
            'description' => 'nullable|string',
            'billing_type' => 'required|in:hourly,fixed,monthly_retainer',
            'default_rate' => 'required|numeric|min:0',
        ]);

        Service::create($validated);

        return redirect()->route('services.index')->with('success', "Layanan {$request->name} berhasil ditambahkan ke katalog!");
    }
}

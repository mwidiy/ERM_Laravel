<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Client::with(['primaryContact', 'projects']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('client_code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $clients = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Client::count(),
            'active' => Client::where('status', 'active')->count(),
            'banking' => Client::where('industry', 'like', '%Banking%')->count(),
            'total_credit' => Client::sum('credit_limit'),
        ];

        return view('clients.index', compact('clients', 'stats', 'search', 'status'));
    }

    public function create()
    {
        // Generate next client code
        $year = date('Y');
        $lastClient = Client::where('client_code', 'like', "CLI-{$year}-%")->latest('id')->first();
        if ($lastClient) {
            $lastNum = (int) substr($lastClient->client_code, -3);
            $nextCode = sprintf("CLI-%s-%03d", $year, $lastNum + 1);
        } else {
            $nextCode = sprintf("CLI-%s-001", $year);
        }

        return view('clients.create', compact('nextCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_code' => 'required|string|unique:clients,client_code',
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:150',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'payment_terms_days' => 'required|integer|min:0',
            'credit_limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
            // Contact PIC fields
            'contact_name' => 'nullable|string|max:255',
            'contact_position' => 'nullable|string|max:150',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $client = Client::create([
                'client_code' => $validated['client_code'],
                'company_name' => $validated['company_name'],
                'industry' => $validated['industry'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'website' => $validated['website'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'tax_id' => $validated['tax_id'] ?? null,
                'payment_terms_days' => $validated['payment_terms_days'],
                'credit_limit' => $validated['credit_limit'] ?? 0,
                'status' => $validated['status'],
            ]);

            if (!empty($validated['contact_name'])) {
                ClientContact::create([
                    'client_id' => $client->id,
                    'name' => $validated['contact_name'],
                    'position' => $validated['contact_position'] ?? 'PIC Utama',
                    'email' => $validated['contact_email'] ?? null,
                    'phone' => $validated['contact_phone'] ?? null,
                    'is_primary' => true,
                ]);
            }
        });

        return redirect()->route('clients.index')->with('success', "Klien {$request->company_name} berhasil ditambahkan!");
    }

    public function show(Client $client)
    {
        $client->load(['contacts', 'projects.projectManager']);
        
        $totalProjectValue = $client->projects->sum('contract_value');
        $activeProjects = $client->projects->whereIn('status', ['planning', 'in_progress'])->count();

        return view('clients.show', compact('client', 'totalProjectValue', 'activeProjects'));
    }

    public function edit(Client $client)
    {
        $client->load('primaryContact');
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:150',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'payment_terms_days' => 'required|integer|min:0',
            'credit_limit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $client->update($validated);

        return redirect()->route('clients.show', $client)->with('success', "Data klien {$client->company_name} berhasil diperbarui!");
    }

    public function destroy(Client $client)
    {
        $name = $client->company_name;
        $client->delete();

        return redirect()->route('clients.index')->with('success', "Klien {$name} berhasil dihapus dari sistem.");
    }
}

@extends('layouts.app')

@section('title', $project->name)
@section('page_title', 'Workspace: ' . $project->project_code)

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Action Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-4 border-b border-slate-800">
        <div class="flex items-start gap-3">
            <a href="{{ route('projects.index') }}" class="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white transition-colors shrink-0 mt-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <span class="text-xs font-mono font-bold px-2 py-0.5 rounded bg-slate-800 text-indigo-400 border border-slate-700/60">{{ $project->project_code }}</span>
                    
                    @php
                        $statusBadge = match($project->status) {
                            'in_progress' => 'bg-sky-500/10 text-sky-400 border-sky-500/30',
                            'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                            'planning' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/30',
                            'on_hold' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                            default => 'bg-slate-800 text-slate-400 border-slate-700'
                        };
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $statusBadge }}">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>

                    @if($project->priority === 'urgent')
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-500/10 text-rose-400 border border-rose-500/30">Urgent</span>
                    @elseif($project->priority === 'high')
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-500/10 text-amber-400 border border-amber-500/30">High Priority</span>
                    @endif

                    <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                        {{ str_replace('_', ' ', $project->contract_type) }}
                    </span>
                </div>

                <h1 class="text-2xl font-black text-white tracking-tight">{{ $project->name }}</h1>
                <p class="text-xs text-slate-400 mt-0.5">
                    @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
                    Klien: <a href="{{ route('clients.show', $project->client) }}" class="text-indigo-400 hover:underline font-bold">{{ $project->client->company_name }}</a> 
                    @else
                    Klien: <span class="text-slate-200 font-bold">{{ $project->client->company_name }}</span>
                    @endif
                    • PM: <strong class="text-slate-200">{{ $project->projectManager->name ?? 'Belum ditentukan' }}</strong>
                </p>
            </div>
        </div>

        @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
        <div class="flex items-center gap-2.5">
            <button onclick="openMilestoneModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                <span>+ Tambah Milestone / Termin</span>
            </button>
            <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 text-xs font-semibold border border-slate-700/80 transition-all">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                <span>Edit Proyek</span>
            </a>
        </div>
        @endif
    </div>

    <!-- 4 Top Project Financial KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Contract Value -->
        <div class="p-5 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-lg">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nilai Kontrak Total</span>
            <div class="text-xl font-black text-white mt-1">Rp {{ number_format($project->contract_value, 0, ',', '.') }}</div>
            <span class="text-[11px] text-indigo-400 mt-1 block">Harga Jual Resmi ke Klien</span>
        </div>

        <!-- Budget Cost RAB & Penyerapan -->
        <div class="p-5 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-lg space-y-1.5">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pagu Anggaran RAB</span>
            <div class="text-xl font-black text-slate-200">Rp {{ number_format($project->budget_cost, 0, ',', '.') }}</div>
            <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1">
                <span>Penyerapan:</span>
                <span class="font-bold text-amber-400">{{ number_format($budgetConsumedPercentage, 1) }}% Terpakai</span>
            </div>
            <div class="w-full h-1.5 rounded-full bg-slate-800 overflow-hidden">
                <div class="h-full rounded-full bg-amber-500" style="width: {{ $budgetConsumedPercentage }}%"></div>
            </div>
        </div>

        <!-- Realisasi Biaya Tenaga Kerja (Labor Cost) -->
        <div class="p-5 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-lg">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Realisasi Biaya Tenaga Kerja</span>
            <div class="text-xl font-black text-sky-400 mt-1">Rp {{ number_format($actualLaborCost, 0, ',', '.') }}</div>
            <span class="text-[11px] text-slate-300 mt-1 block">Dari {{ number_format($totalLoggedHours, 1) }} jam disetujui</span>
        </div>

        <!-- Termin Siap / Terfaktur -->
        <div class="p-5 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-lg">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Termin Siap / Terfaktur</span>
            <div class="text-xl font-black text-emerald-400 mt-1">Rp {{ number_format($invoicedAmount, 0, ',', '.') }}</div>
            <span class="text-[11px] text-emerald-400 mt-1 block font-medium">Dari {{ $project->milestones->count() }} Jadwal Termin</span>
        </div>

    </div>

    <!-- Navigation Tabs Bar -->
    <div class="border-b border-slate-800">
        <nav class="flex flex-wrap gap-6 text-sm font-semibold">
            <button onclick="switchTab('overview')" id="tab-btn-overview" class="pb-3 border-b-2 border-indigo-500 text-indigo-400 flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span>Ringkasan & WBS Scope</span>
            </button>

            <button onclick="switchTab('milestones')" id="tab-btn-milestones" class="pb-3 border-b-2 border-transparent text-slate-400 hover:text-white flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                <span>Termin & Deliverables ({{ $project->milestones->count() }})</span>
            </button>

            <button onclick="switchTab('tasks')" id="tab-btn-tasks" class="pb-3 border-b-2 border-transparent text-slate-400 hover:text-white flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                <span>Tugas Teknis ({{ $project->tasks->count() }})</span>
            </button>

            <button onclick="switchTab('timesheets')" id="tab-btn-timesheets" class="pb-3 border-b-2 border-transparent text-slate-400 hover:text-white flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Timesheet & Biaya ({{ $project->timesheets->count() }})</span>
            </button>

            <button onclick="switchTab('team')" id="tab-btn-team" class="pb-3 border-b-2 border-transparent text-slate-400 hover:text-white flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <span>Alokasi Tim ({{ $project->members->count() }})</span>
            </button>

            @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'finance', 'project_manager']))
            <button onclick="switchTab('finance')" id="tab-btn-finance" class="pb-3 border-b-2 border-transparent text-slate-400 hover:text-white flex items-center gap-2 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Faktur & Beban ({{ $project->invoices->count() + $project->expenses->count() }})</span>
            </button>
            @endif
        </nav>
    </div>

    <!-- TAB 1: OVERVIEW & WBS SCOPE -->
    <div id="tab-content-overview" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left: Scope & Delivery -->
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 shadow-xl space-y-5">
                    <h2 class="text-base font-bold text-white border-b border-slate-800 pb-3">Deskripsi & Ruang Lingkup Pekerjaan (Scope of Work)</h2>
                    <div class="text-sm text-slate-300 leading-relaxed whitespace-pre-line">
                        {{ $project->description ?: 'Belum ada uraian ruang lingkup detail yang dicatat.' }}
                    </div>

                    <!-- Delivery Progress Widget -->
                    <div class="pt-4 border-t border-slate-800 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-white">Akumulasi Progres Fisik Proyek</span>
                            <span class="font-black text-indigo-400 text-base">{{ $project->progress_percentage }}% Selesai</span>
                        </div>
                        <div class="w-full h-3 rounded-full bg-slate-950 border border-slate-800 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-sky-400 transition-all duration-500" style="width: {{ $project->progress_percentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Project Key Details -->
            <div class="space-y-6">
                <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 shadow-xl space-y-4">
                    <h3 class="font-bold text-white text-base border-b border-slate-800 pb-3">Informasi Pelaksanaan</h3>

                    <div class="space-y-3 text-xs">
                        <div>
                            <span class="text-slate-400 block uppercase font-semibold text-[10px]">Project Delivery Manager (PM)</span>
                            <span class="text-white font-bold text-sm">{{ $project->projectManager->name ?? 'Belum Di-assign' }}</span>
                            <span class="text-slate-400 block text-[11px]">{{ $project->projectManager->email ?? '' }}</span>
                        </div>

                        <div>
                            <span class="text-slate-400 block uppercase font-semibold text-[10px]">Klien Korporasi</span>
                            @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
                                <a href="{{ route('clients.show', $project->client) }}" class="text-indigo-400 hover:underline font-bold text-sm">{{ $project->client->company_name }}</a>
                            @else
                                <span class="text-slate-200 font-bold text-sm">{{ $project->client->company_name }}</span>
                            @endif
                            <span class="text-slate-400 block text-[11px]">{{ $project->client->city }}</span>
                        </div>

                        <div>
                            <span class="text-slate-400 block uppercase font-semibold text-[10px]">Tanggal Kickoff Proyek</span>
                            <span class="text-slate-200 font-medium">{{ $project->start_date ? $project->start_date->format('d F Y') : '-' }}</span>
                        </div>

                        <div>
                            <span class="text-slate-400 block uppercase font-semibold text-[10px]">Target Serah Terima (BAST)</span>
                            <span class="text-slate-200 font-medium">{{ $project->end_date ? $project->end_date->format('d F Y') : 'Ongoing' }}</span>
                        </div>

                        <div>
                            <span class="text-slate-400 block uppercase font-semibold text-[10px]">Model Kontrak</span>
                            <span class="text-slate-200 font-medium uppercase">{{ str_replace('_', ' ', $project->contract_type) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- TAB 2: MILESTONES & JADWAL TERMIN (BILLING SCHEDULE) -->
    <div id="tab-content-milestones" class="hidden space-y-6">
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-white tracking-tight">Rencana Termin Penagihan & Deliverables (WBS)</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Penetapan syarat penagihan faktur berdasarkan persentase pencapaian deliverable</p>
                </div>
                @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
                <button onclick="openMilestoneModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    <span>+ Tambah Termin Baru</span>
                </button>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Nama Deliverable / Termin</th>
                            <th class="px-6 py-4">Target Tanggal</th>
                            <th class="px-6 py-4">% Selesai</th>
                            <th class="px-6 py-4">% Termin</th>
                            <th class="px-6 py-4">Nominal Tagihan (IDR)</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Faktur & Tagihan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($project->milestones as $m)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white">{{ $m->title }}</div>
                                    @if($m->description)
                                        <div class="text-xs text-slate-400 mt-0.5 max-w-md">{{ $m->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-300">
                                    {{ $m->due_date ? $m->due_date->format('d M Y') : 'TBD' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 h-1.5 rounded-full bg-slate-800 overflow-hidden">
                                            <div class="h-full rounded-full bg-indigo-500" style="width: {{ $m->completion_percentage }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold text-white">{{ $m->completion_percentage }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-200">
                                    {{ $m->billing_percentage }}%
                                </td>
                                <td class="px-6 py-4 font-bold text-emerald-400">
                                    Rp {{ number_format($m->billing_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $mClass = match($m->status) {
                                            'invoiced' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                            'completed' => 'bg-sky-500/10 text-sky-400 border-sky-500/30',
                                            'in_progress' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/30',
                                            default => 'bg-slate-800 text-slate-400 border-slate-700'
                                        };
                                        $mLabel = match($m->status) {
                                            'invoiced' => 'Terfaktur / Paid',
                                            'completed' => 'Deliverable Ready',
                                            'in_progress' => 'Dalam Pengerjaan',
                                            default => 'Pending'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $mClass }}">
                                        {{ $mLabel }}
                                    </span>
                                <td class="px-6 py-4 text-right">
                                    @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'finance', 'project_manager']))
                                        @if($m->invoice)
                                            <a href="{{ route('invoices.show', $m->invoice->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-500/10 text-indigo-400 hover:text-white border border-indigo-500/20 text-xs font-mono font-bold transition-colors">
                                                <i class="fa-solid fa-file-invoice"></i> {{ $m->invoice->invoice_number }}
                                            </a>
                                        @else
                                            <a href="{{ route('invoices.create', ['project_id' => $project->id, 'milestone_id' => $m->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 text-xs font-semibold border border-indigo-500/30 transition-all">
                                                <i class="fa-solid fa-plus text-[10px]"></i> Terbitkan Faktur
                                            </a>
                                        @endif
                                    @else
                                        @if($m->invoice)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-800 text-slate-400 border border-slate-700/60 text-xs font-mono">
                                                <i class="fa-solid fa-file-invoice"></i> {{ $m->invoice->invoice_number }}
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-500">Termin Terjadwal</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-400">
                                    Belum ada milestone atau jadwal termin penagihan yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 3: TUGAS TEKNIS (WBS TASKS) -->
    <div id="tab-content-tasks" class="hidden space-y-6">
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-white tracking-tight">Tugas Teknis & Deliverables WBS Proyek</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar pekerjaan terinci, alokasi personil teknis, dan estimasi jam kerja</p>
                </div>
                <button onclick="openProjectTaskModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    <span>+ Tambah Tugas WBS</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Judul Tugas</th>
                            <th class="px-6 py-4">Milestone Terkait</th>
                            <th class="px-6 py-4">Penanggung Jawab</th>
                            <th class="px-6 py-4 text-center">Jam (Aktual / Est)</th>
                            <th class="px-6 py-4">Prioritas</th>
                            <th class="px-6 py-4 text-center">Status Tahapan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($project->tasks as $t)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white">{{ $t->title }}</div>
                                    @if($t->description)
                                        <div class="text-xs text-slate-400 mt-0.5 max-w-md line-clamp-1">{{ $t->description }}</div>
                                    @endif
                                    <span class="text-[10px] text-slate-400 block mt-1">Target: {{ $t->due_date ? $t->due_date->format('d M Y') : 'TBD' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($t->milestone)
                                        <span class="text-xs text-indigo-300 bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-500/20 font-medium">
                                            {{ $t->milestone->title }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">Umum / Tanpa Milestone</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center font-bold text-white text-[10px]">
                                            {{ strtoupper(substr($t->assignedUser->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="text-xs font-medium text-slate-200">{{ $t->assignedUser->name ?? 'Unassigned' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-white text-xs">{{ $t->actual_hours }} / {{ $t->estimated_hours }}</span>
                                    <span class="text-[10px] text-slate-400 block">Jam</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($t->priority === 'urgent')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-500/10 text-rose-400 border border-rose-500/30">Urgent</span>
                                    @elseif($t->priority === 'high')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-500/10 text-amber-400 border border-amber-500/30">High</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-800 text-slate-400">Medium</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('tasks.updateStatus', $t) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="px-2 py-1 rounded bg-slate-900 border border-slate-700/80 text-xs text-slate-300 focus:outline-none focus:border-indigo-500">
                                            <option value="todo" {{ $t->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                            <option value="in_progress" {{ $t->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="review" {{ $t->status === 'review' ? 'selected' : '' }}>Review</option>
                                            <option value="done" {{ $t->status === 'done' ? 'selected' : '' }}>Done</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                    Belum ada tugas teknis yang didaftarkan pada proyek ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 4: TIMESHEET & BIAYA TENAGA KERJA -->
    <div id="tab-content-timesheets" class="hidden space-y-6">
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-white tracking-tight">Log Jam Kerja & Beban Tenaga Kerja Riil Proyek</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Kalkulasi biaya langsung tenaga kerja dari timesheet yang telah diverifikasi</p>
                </div>
                <button onclick="openProjectTimesheetModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    <span>+ Catat Jam Kerja Proyek Ini</span>
                </button>
            </div>

            <!-- Timesheet Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-6 bg-slate-950/40 border-b border-slate-800">
                <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800">
                    <span class="text-xs text-slate-400 uppercase font-semibold">Total Jam Disetujui</span>
                    <div class="text-xl font-black text-white mt-1">{{ number_format($totalLoggedHours, 1) }} Jam</div>
                </div>
                <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800">
                    <span class="text-xs text-slate-400 uppercase font-semibold">Total Biaya Tenaga Kerja (Labor)</span>
                    <div class="text-xl font-black text-sky-400 mt-1">Rp {{ number_format($actualLaborCost, 0, ',', '.') }}</div>
                </div>
                <div class="p-4 rounded-xl bg-slate-900/80 border border-slate-800">
                    <span class="text-xs text-slate-400 uppercase font-semibold">Sisa Anggaran RAB</span>
                    <div class="text-xl font-black text-emerald-400 mt-1">Rp {{ number_format($remainingBudget, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Tanggal & Personil</th>
                            <th class="px-6 py-4">Uraian Aktivitas</th>
                            <th class="px-6 py-4 text-center">Durasi (Jam)</th>
                            <th class="px-6 py-4">Tarif Biaya / Jam</th>
                            <th class="px-6 py-4">Total Biaya (Cost)</th>
                            <th class="px-6 py-4 text-center">Status Approval</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($project->timesheets as $ts)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white">{{ $ts->entry_date->format('d M Y') }}</div>
                                    <div class="text-xs text-slate-300 font-medium mt-0.5">{{ $ts->user->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($ts->task)
                                        <span class="text-[10px] font-semibold text-indigo-300 block mb-0.5">Tugas: {{ $t->title ?? '' }}</span>
                                    @endif
                                    <div class="text-xs text-slate-300 max-w-sm">{{ $ts->description }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-white text-base">{{ number_format($ts->hours, 1) }}</span>
                                    <span class="text-[10px] text-slate-400 block">Jam</span>
                                </td>
                                <td class="px-6 py-4 text-slate-300">
                                    Rp {{ number_format($ts->hourly_rate, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 font-bold text-sky-400">
                                    Rp {{ number_format($ts->total_cost, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($ts->status === 'approved')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                            Disetujui
                                        </span>
                                    @elseif($ts->status === 'rejected')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                    Belum ada log jam kerja tercatat untuk proyek ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 5: ALOKASI TIM & PERSONIL -->
    <div id="tab-content-team" class="hidden space-y-6">
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-white tracking-tight">Alokasi Anggota Tim & Konsultan Proyek</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Penugasan personil internal beserta peran dan tarif per jam untuk kalkulasi beban tenaga kerja</p>
                </div>
                @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
                <button onclick="openMemberModal()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    <span>+ Tugaskan Personil Baru</span>
                </button>
                @endif
            </div>

            <div class="divide-y divide-slate-800/60">
                @forelse($project->members as $member)
                    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-800/20 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center font-bold text-white text-xs ring-2 ring-indigo-500/30 shrink-0">
                                {{ strtoupper(substr($member->name, 0, 2)) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-sm">{{ $member->name }}</h3>
                                <div class="text-xs text-indigo-400 font-medium">{{ $member->pivot->project_role ?? 'Team Member' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $member->position ?? 'Konsultan' }} • {{ $member->email }}</div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-6 text-xs">
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Tarif Biaya Tenaga Kerja</span>
                                <span class="font-bold text-white text-sm">Rp {{ number_format($member->pivot->hourly_rate, 0, ',', '.') }}</span>
                                <span class="text-slate-400 block text-[10px]">/ Jam Kerja</span>
                            </div>

                            @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
                            <form action="{{ route('projects.members.remove', ['project' => $project, 'user' => $member]) }}" method="POST" onsubmit="return confirm('Lepaskan personil {{ $member->name }} dari proyek ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg bg-slate-800 hover:bg-rose-950/60 text-slate-400 hover:text-rose-400 border border-slate-700/60 transition-colors" title="Lepas Personil">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-sm">
                        Belum ada anggota tim yang ditugaskan pada proyek ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'finance', 'project_manager']))
    <!-- TAB 6: FAKTUR & BEBAN PROYEK (FINANCIAL & AR LEDGER) -->
    <div id="tab-content-finance" class="hidden space-y-6">
        
        <!-- Financial Metric Strip -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Total Nilai Tagihan Terbit</span>
                <div class="text-lg font-black text-white mt-1">Rp {{ number_format($project->totalInvoiced(), 0, ',', '.') }}</div>
                <span class="text-[10px] text-indigo-400 mt-0.5 block">{{ $project->invoices->count() }} Faktur Penagihan</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Kas Masuk (Collected)</span>
                <div class="text-lg font-black text-emerald-400 mt-1">Rp {{ number_format($project->totalCollected(), 0, ',', '.') }}</div>
                <span class="text-[10px] text-emerald-300 mt-0.5 block">Terkonfirmasi Masuk Rekening</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Sisa Piutang Proyek (AR)</span>
                <div class="text-lg font-black text-amber-400 mt-1">Rp {{ number_format($project->outstandingAR(), 0, ',', '.') }}</div>
                <span class="text-[10px] text-amber-300 mt-0.5 block">Belum Dilunasi Klien</span>
            </div>
            <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Beban Langsung (Direct Expense)</span>
                <div class="text-lg font-black text-cyan-400 mt-1">Rp {{ number_format($project->approvedDirectExpenses(), 0, ',', '.') }}</div>
                <span class="text-[10px] text-slate-400 mt-0.5 block">{{ $project->expenses->count() }} Pengeluaran Dicatat</span>
            </div>
        </div>

        <!-- Section 1: Invoices Table -->
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden">
            <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-file-invoice-dollar text-indigo-400"></i>
                        Daftar Faktur Penagihan Klien
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Penagihan termin kontrak, PPN 11%, dan PPh 23 Jasa untuk proyek ini</p>
                </div>
                <a href="{{ route('invoices.create', ['project_id' => $project->id]) }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition-all shadow-md shadow-indigo-600/30">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>Terbitkan Faktur Baru</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="px-5 py-3.5">Nomor & Tanggal</th>
                            <th class="px-5 py-3.5">Termin Milestone</th>
                            <th class="px-5 py-3.5">Jatuh Tempo</th>
                            <th class="px-5 py-3.5 text-right">Nilai Faktur</th>
                            <th class="px-5 py-3.5 text-right">Sisa Piutang</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($project->invoices as $inv)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <a href="{{ route('invoices.show', $inv->id) }}" class="font-mono text-xs font-bold text-indigo-400 hover:underline">
                                        {{ $inv->invoice_number }}
                                    </a>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $inv->issue_date->format('d M Y') }}</div>
                                </td>
                                <td class="px-5 py-3.5">
                                    {{ $inv->milestone->title ?? 'Tagihan Reguler' }}
                                </td>
                                <td class="px-5 py-3.5 text-xs">
                                    {{ $inv->due_date->format('d M Y') }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-bold text-white whitespace-nowrap">
                                    Rp {{ number_format($inv->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-bold whitespace-nowrap {{ $inv->remaining_balance > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                                    Rp {{ number_format($inv->remaining_balance, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    {!! $inv->status_badge !!}
                                </td>
                                <td class="px-5 py-3.5 text-right space-x-1 whitespace-nowrap">
                                    <a href="{{ route('invoices.print', $inv->id) }}" target="_blank" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white inline-block text-xs" title="Cetak Faktur">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <a href="{{ route('invoices.show', $inv->id) }}" class="p-1.5 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 border border-indigo-500/30 inline-block text-xs" title="Detail & Pelunasan">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-6 text-center text-slate-500 text-xs">
                                    Belum ada faktur penagihan yang diterbitkan untuk proyek ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 2: Direct Expenses Table -->
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden">
            <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-cyan-400"></i>
                        Beban & Pengeluaran Langsung Proyek
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Biaya non-tenaga kerja: transport, lisensi cloud, perlengkapan, dan konsumsi</p>
                </div>
                <a href="{{ route('expenses.index', ['project_id' => $project->id]) }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold transition-all">
                    <span>Lihat di Hub Beban</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="px-5 py-3.5">Kode & Tanggal</th>
                            <th class="px-5 py-3.5">Kategori & Judul</th>
                            <th class="px-5 py-3.5">Diajukan Oleh</th>
                            <th class="px-5 py-3.5 text-right">Nominal</th>
                            <th class="px-5 py-3.5 text-center">Billable</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($project->expenses as $exp)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="font-mono text-xs font-bold text-indigo-400">{{ $exp->expense_code }}</span>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $exp->expense_date->format('d M Y') }}</div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-medium text-white">{{ $exp->title }}</div>
                                    <div class="text-[11px] text-indigo-400">{{ $exp->category_label }}</div>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-300">
                                    {{ $exp->user->name }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-bold text-white whitespace-nowrap">
                                    Rp {{ number_format($exp->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if($exp->is_billable_to_client)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">Billable</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-800 text-slate-400">Non-Billable</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    {!! $exp->status_badge !!}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-6 text-center text-slate-500 text-xs">
                                    Belum ada pengeluaran langsung yang dicatat untuk proyek ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    @endif

</div>

@if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
<!-- MODAL 1: TAMBAH MILESTONE -->
<div id="milestone-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white">Tambah Jadwal Termin & Deliverable</h3>
            <button onclick="closeMilestoneModal()" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form action="{{ route('projects.milestones.store', $project) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Judul Termin / Deliverable <span class="text-rose-400">*</span></label>
                <input type="text" name="title" placeholder="Contoh: Termin 2: Integrasi Core Banking & UAT" required
                       class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Uraian Deliverable</label>
                <textarea name="description" rows="2" placeholder="Kriteria penyelesaian untuk penerbitan faktur..."
                          class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Target Tanggal Selesai</label>
                    <input type="date" name="due_date" value="{{ now()->addWeeks(4)->format('Y-m-d') }}"
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">% Termin Kontrak <span class="text-rose-400">*</span></label>
                    <input type="number" name="billing_percentage" id="input_billing_pct" value="30" min="1" max="100" step="0.5" required
                           oninput="calcBillingAmount()"
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Nominal Tagihan (Rp) <span class="text-rose-400">*</span></label>
                    <input type="number" name="billing_amount" id="input_billing_amount" value="{{ $project->contract_value * 0.30 }}" min="0" required
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm font-semibold text-emerald-400 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Status Termin</label>
                    <select name="status" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="pending">Pending (Menunggu)</option>
                        <option value="in_progress">Dalam Pengerjaan</option>
                        <option value="completed">Deliverable Selesai</option>
                        <option value="invoiced">Terfaktur / Terbayar</option>
                    </select>
                </div>
            </div>

            <input type="hidden" name="completion_percentage" value="0">

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                <button type="button" onclick="closeMilestoneModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30">Simpan Termin</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: TUGASKAN PERSONIL -->
<div id="member-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white">Tugaskan Personil ke Proyek</h3>
            <button onclick="closeMemberModal()" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form action="{{ route('projects.members.assign', $project) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Pilih Personil Internal <span class="text-rose-400">*</span></label>
                <select name="user_id" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                    <option value="">-- Pilih Anggota Tim --</option>
                    @foreach($allUsers as $u)
                        <option value="{{ $u->id }}">
                            {{ $u->name }} ({{ $u->position ?? 'Konsultan' }} - Tarif: Rp {{ number_format($u->hourly_rate, 0, ',', '.') }}/jam)
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Peran Pada Proyek Ini <span class="text-rose-400">*</span></label>
                <input type="text" name="project_role" placeholder="Contoh: Lead Software Architect, QA Lead, UI Designer" required
                       class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Tarif Biaya Per Jam (IDR / Jam)</label>
                <input type="number" name="hourly_rate" placeholder="Kosongkan untuk memakai tarif default personil" min="0" step="5000"
                       class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                <button type="button" onclick="closeMemberModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30">Tugaskan Personil</button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- MODAL 3: TAMBAH TUGAS PROYEK -->
<div id="project-task-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white">Tambah Tugas WBS Proyek</h3>
            <button onclick="closeProjectTaskModal()" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Judul Tugas <span class="text-rose-400">*</span></label>
                <input type="text" name="title" placeholder="Contoh: Implementasi modul Redis Cache" required
                       class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Milestone Terkait</label>
                    <select name="milestone_id" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="">-- Tanpa Milestone --</option>
                        @foreach($project->milestones as $m)
                            <option value="{{ $m->id }}">{{ $m->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Personil Pelaksana</label>
                    <select name="assigned_to" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="">-- Pilih Anggota Tim --</option>
                        @foreach($project->members as $member)
                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Estimasi Jam Kerja</label>
                    <input type="number" name="estimated_hours" value="16" min="0" step="0.5"
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Target Selesai</label>
                    <input type="date" name="due_date" value="{{ now()->addDays(7)->format('Y-m-d') }}"
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Prioritas</label>
                    <select name="priority" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                        <option value="low">Low</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Tahap Awal</label>
                    <select name="status" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="review">Review</option>
                        <option value="done">Done</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                <button type="button" onclick="closeProjectTaskModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30">Simpan Tugas</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 4: CATAT JAM KERJA PROYEK INI -->
<div id="project-timesheet-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white">Catat Jam Kerja Proyek Ini</h3>
            <button onclick="closeProjectTimesheetModal()" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form action="{{ route('timesheets.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Personil Yang Mengerjakan <span class="text-rose-400">*</span></label>
                <select name="user_id" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                    @foreach($project->members as $member)
                        <option value="{{ $member->id }}">
                            {{ $member->name }} ({{ $member->pivot->project_role ?? 'Konsultan' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Tugas Terkait (Opsional)</label>
                <select name="task_id" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                    <option value="">-- Pekerjaan Umum / Tanpa Task Spesifik --</option>
                    @foreach($project->tasks as $t)
                        <option value="{{ $t->id }}">{{ $t->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Tanggal Pengerjaan</label>
                    <input type="date" name="entry_date" value="{{ now()->format('Y-m-d') }}" required
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Durasi Jam Kerja <span class="text-rose-400">*</span></label>
                    <input type="number" name="hours" value="8" min="0.25" max="24" step="0.25" required
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm font-bold text-indigo-400 focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-950/60 border border-slate-800">
                <input type="checkbox" name="is_billable" value="1" id="proj_is_billable" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                <label for="proj_is_billable" class="text-xs text-slate-300 font-medium">
                    <strong class="text-white block">Dapat Ditagihkan ke Klien (Billable Hours)</strong>
                </label>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Uraian Aktivitas Teknis <span class="text-rose-400">*</span></label>
                <textarea name="description" rows="3" placeholder="Uraikan detail pekerjaan yang diselesaikan..." required
                          class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                <button type="button" onclick="closeProjectTimesheetModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30">Catat Jam</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const contractValue = {{ $project->contract_value }};

    function calcBillingAmount() {
        const pct = parseFloat(document.getElementById('input_billing_pct').value) || 0;
        const amount = (contractValue * pct) / 100;
        document.getElementById('input_billing_amount').value = Math.round(amount);
    }

    function switchTab(tabId) {
        ['overview', 'milestones', 'tasks', 'timesheets', 'team', 'finance'].forEach(id => {
            const content = document.getElementById('tab-content-' + id);
            const btn = document.getElementById('tab-btn-' + id);
            
            if (content && btn) {
                if (id === tabId) {
                    content.classList.remove('hidden');
                    btn.classList.add('border-indigo-500', 'text-indigo-400');
                    btn.classList.remove('border-transparent', 'text-slate-400');
                } else {
                    content.classList.add('hidden');
                    btn.classList.remove('border-indigo-500', 'text-indigo-400');
                    btn.classList.add('border-transparent', 'text-slate-400');
                }
            }
        });
    }

    // Modal controllers
    function openMilestoneModal() {
        const modal = document.getElementById('milestone-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeMilestoneModal() {
        const modal = document.getElementById('milestone-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openMemberModal() {
        const modal = document.getElementById('member-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeMemberModal() {
        const modal = document.getElementById('member-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openProjectTaskModal() {
        const modal = document.getElementById('project-task-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeProjectTaskModal() {
        const modal = document.getElementById('project-task-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openProjectTimesheetModal() {
        const modal = document.getElementById('project-timesheet-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeProjectTimesheetModal() {
        const modal = document.getElementById('project-timesheet-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Check URL param ?tab=
    const urlParams = new URLSearchParams(window.location.search);
    const initialTab = urlParams.get('tab');
    if (initialTab && ['milestones', 'tasks', 'timesheets', 'team', 'overview', 'finance'].includes(initialTab)) {
        switchTab(initialTab);
    }
</script>
@endpush
@endsection

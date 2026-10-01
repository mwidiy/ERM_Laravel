@extends('layouts.app')

@section('page_title', 'Analisis Laba Rugi: ' . $project->name)

@section('content')
<div class="space-y-6">

    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('profitability.index') }}" class="hover:text-white transition-colors">Laba Rugi Proyek</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-600"></i>
                <span class="font-mono text-indigo-400">{{ $project->project_code }}</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">{{ $project->name }}</h1>
            <p class="text-xs text-slate-400 mt-0.5">
                Klien: <span class="text-white font-semibold">{{ $project->client->company_name }}</span> • 
                Project Manager: <span class="text-slate-200">{{ $project->projectManager->name ?? '-' }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('projects.show', $project->id) }}" class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs font-semibold border border-slate-700/80 transition-all">
                <i class="fa-solid fa-arrow-up-right-from-square mr-1.5"></i> Workspace Proyek
            </a>
            <a href="{{ route('profitability.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold transition-all">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali
            </a>
        </div>
    </div>

    <!-- 4 Main Project Financial KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Contract Value -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Nilai Kontrak Total</div>
            <div class="mt-2 text-2xl font-extrabold text-white">Rp {{ number_format($contractVal, 0, ',', '.') }}</div>
            <div class="mt-2 flex items-center text-xs text-indigo-400 font-medium">
                <i class="fa-solid fa-file-contract mr-1.5"></i> Pendapatan Basis Kontrak
            </div>
        </div>

        <!-- Total Actual Cost (COGS) -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Biaya Aktual Riil</div>
            <div class="mt-2 text-2xl font-extrabold text-cyan-400">Rp {{ number_format($totalCost, 0, ',', '.') }}</div>
            <div class="mt-2 flex items-center text-xs text-slate-400 font-medium">
                <i class="fa-solid fa-calculator mr-1.5"></i> Labor (Rp {{ number_format($laborCost, 0, ',', '.') }}) + Direct (Rp {{ number_format($directExpense, 0, ',', '.') }})
            </div>
        </div>

        <!-- Gross Profit -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Laba Kotor Proyek (Gross Profit)</div>
            <div class="mt-2 text-2xl font-extrabold {{ $grossProfit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                Rp {{ number_format($grossProfit, 0, ',', '.') }}
            </div>
            <div class="mt-2 flex items-center text-xs font-medium {{ $grossProfit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                <i class="fa-solid {{ $grossProfit >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1.5"></i>
                Margin Laba: {{ $marginPct }}%
            </div>
        </div>

        <!-- Budget Consumption Rate -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            @php
                $budgetBurn = $project->budget_cost > 0 ? round(($totalCost / $project->budget_cost) * 100, 1) : 0;
            @endphp
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Penyerapan Anggaran RAB</div>
            <div class="mt-2 text-2xl font-extrabold {{ $budgetBurn > 100 ? 'text-rose-400' : ($budgetBurn >= 80 ? 'text-amber-400' : 'text-emerald-400') }}">
                {{ $budgetBurn }}%
            </div>
            <div class="mt-2 flex items-center text-xs text-slate-400">
                Pagu RAB: Rp {{ number_format($project->budget_cost, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Budget Burn vs Contract Progress Bar -->
    <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm space-y-4">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
            <i class="fa-solid fa-scale-balanced text-indigo-400"></i>
            Perbandingan Anggaran Biaya vs Realisasi Finansial
        </h3>

        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-xs font-medium mb-1">
                    <span class="text-slate-400">Penyerapan Biaya Riil (Actual Cost vs RAB)</span>
                    <span class="font-bold text-white">Rp {{ number_format($totalCost, 0, ',', '.') }} dari Rp {{ number_format($project->budget_cost, 0, ',', '.') }} ({{ $budgetBurn }}%)</span>
                </div>
                <div class="w-full bg-slate-950 rounded-full h-3 overflow-hidden border border-slate-800">
                    <div class="h-3 rounded-full {{ $budgetBurn > 100 ? 'bg-rose-500' : ($budgetBurn >= 80 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ min(100, $budgetBurn) }}%"></div>
                </div>
            </div>

            <div>
                <div class="flex justify-between text-xs font-medium mb-1">
                    <span class="text-slate-400">Progres Fisik Delivery (WBS Deliverables)</span>
                    <span class="font-bold text-indigo-400">{{ $project->progress_percentage }}% Selesai</span>
                </div>
                <div class="w-full bg-slate-950 rounded-full h-2 overflow-hidden border border-slate-800">
                    <div class="bg-gradient-to-r from-indigo-500 to-sky-400 h-2 rounded-full" style="width: {{ $project->progress_percentage }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Column Breakdown Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Column 1: Labor Cost by Team Member -->
        <div class="rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-users-gear text-indigo-400"></i>
                        Biaya Tenaga Kerja (Labor Cost)
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Akumulasi jam kerja timesheet yang telah disetujui (Approved)</p>
                </div>
                <span class="font-mono text-xs font-bold text-white">Rp {{ number_format($laborCost, 0, ',', '.') }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="text-xs uppercase bg-slate-950/70 text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Nama Konsultan / Engineer</th>
                            <th class="px-5 py-3 font-semibold text-center">Total Jam</th>
                            <th class="px-5 py-3 font-semibold text-right">Total Biaya Diserap</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($laborByUser as $userLabor)
                            <tr>
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-white">{{ $userLabor->user_name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $userLabor->position }}</div>
                                </td>
                                <td class="px-5 py-3 text-center font-mono font-medium text-slate-200">
                                    {{ $userLabor->total_hours }} Jam
                                </td>
                                <td class="px-5 py-3 text-right font-mono font-bold text-white">
                                    Rp {{ number_format($userLabor->total_cost, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-6 text-center text-slate-500 text-xs">
                                    Belum ada timesheet tenaga kerja yang disetujui untuk proyek ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Column 2: Direct Expenses by Category -->
        <div class="rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-cyan-400"></i>
                        Beban Langsung (Direct Expenses)
                    </h3>
                    <p class="text-xs text-slate-400 mt-0.5">Biaya non-tenaga kerja per kategori pengeluaran</p>
                </div>
                <span class="font-mono text-xs font-bold text-cyan-400">Rp {{ number_format($directExpense, 0, ',', '.') }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="text-xs uppercase bg-slate-950/70 text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Klasifikasi Kategori</th>
                            <th class="px-5 py-3 font-semibold text-center">Jumlah Item</th>
                            <th class="px-5 py-3 font-semibold text-right">Total Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($expenseByCategory as $catExpense)
                            <tr>
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-white">{{ $catExpense->category_label }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ $catExpense->category }}</div>
                                </td>
                                <td class="px-5 py-3 text-center font-mono font-medium text-slate-200">
                                    {{ $catExpense->count }} Pengajuan
                                </td>
                                <td class="px-5 py-3 text-right font-mono font-bold text-cyan-400">
                                    Rp {{ number_format($catExpense->total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-6 text-center text-slate-500 text-xs">
                                    Belum ada beban langsung yang disetujui untuk proyek ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Billing & Cashflow Realization Table -->
    <div class="rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar text-emerald-400"></i>
                    Realisasi Arus Kas & Piutang Usaha Proyek
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Status penagihan faktur termin, penerimaan kas, dan sisa piutang</p>
            </div>
            <div class="flex items-center gap-4 text-xs font-mono">
                <div>Ditagihkan: <strong class="text-white">Rp {{ number_format($invoiced, 0, ',', '.') }}</strong></div>
                <div>Kas Masuk: <strong class="text-emerald-400">Rp {{ number_format($collected, 0, ',', '.') }}</strong></div>
                <div>Sisa Piutang: <strong class="text-amber-400">Rp {{ number_format($outstandingAR, 0, ',', '.') }}</strong></div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs uppercase bg-slate-950/70 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-3.5">Nomor Faktur</th>
                        <th class="px-5 py-3.5">Termin / Milestone</th>
                        <th class="px-5 py-3.5">Tanggal Terbit</th>
                        <th class="px-5 py-3.5 text-right">Nilai Faktur</th>
                        <th class="px-5 py-3.5 text-right">Kas Diterima</th>
                        <th class="px-5 py-3.5 text-right">Sisa Piutang</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($project->invoices as $inv)
                        <tr>
                            <td class="px-5 py-3.5 font-mono font-bold text-indigo-400">
                                <a href="{{ route('invoices.show', $inv->id) }}" class="hover:underline">{{ $inv->invoice_number }}</a>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-slate-300">{{ $inv->milestone->title ?? 'Tagihan Bebas' }}</td>
                            <td class="px-5 py-3.5 text-xs">{{ $inv->issue_date->format('d M Y') }}</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold text-white">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-right font-mono text-emerald-400 font-bold">Rp {{ number_format($inv->paid_amount, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-right font-mono font-bold {{ $inv->remaining_balance > 0 ? 'text-amber-400' : 'text-slate-500' }}">Rp {{ number_format($inv->remaining_balance, 0, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-center">{!! $inv->status_badge !!}</td>
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

</div>
@endsection

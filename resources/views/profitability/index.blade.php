@extends('layouts.app')

@section('page_title', 'Laba Rugi per Proyek (Profitability)')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                <span class="w-2.5 h-6 bg-emerald-500 rounded-full inline-block"></span>
                Analisis Laba Rugi Proyek (Project Profitability)
            </h1>
            <p class="text-sm text-slate-400 mt-1">Visibilitas margin laba kotor riil: Pendapatan Kontrak dikurangi Biaya Tenaga Kerja (Timesheet) & Beban Langsung.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('profitability.arAging') }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs font-semibold border border-slate-700/80 transition-all">
                <i class="fa-solid fa-clock-rotate-left text-amber-400"></i>
                <span>Jadwal Umur Piutang (Aging)</span>
            </a>
            <a href="{{ route('profitability.print') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-lg shadow-indigo-600/30 transition-all active:scale-95">
                <i class="fa-solid fa-print"></i>
                <span>Cetak Laporan Direksi</span>
            </a>
        </div>
    </div>

    <!-- 4 Main Executive Financial KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Portfolio Contract Value -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Nilai Kontrak Portofolio</div>
            <div class="mt-2 text-2xl font-extrabold text-white">Rp {{ number_format($portfolioContractValue, 0, ',', '.') }}</div>
            <div class="mt-2 flex items-center text-xs text-indigo-400 font-medium">
                <i class="fa-solid fa-briefcase mr-1.5"></i> Seluruh proyek aktif & berjalan
            </div>
        </div>

        <!-- Total Actual Cost (COGS) -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Beban Riil (HPP Proyek)</div>
            <div class="mt-2 text-2xl font-extrabold text-cyan-400">Rp {{ number_format($portfolioTotalCost, 0, ',', '.') }}</div>
            <div class="mt-2 flex items-center text-xs text-slate-400 font-medium">
                <i class="fa-solid fa-calculator mr-1.5"></i> Tenaga Kerja + Beban Langsung
            </div>
        </div>

        <!-- Portfolio Gross Profit -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Laba Kotor Portofolio (Gross Profit)</div>
            <div class="mt-2 text-2xl font-extrabold {{ $portfolioGrossProfit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                Rp {{ number_format($portfolioGrossProfit, 0, ',', '.') }}
            </div>
            <div class="mt-2 flex items-center text-xs font-medium {{ $portfolioGrossProfit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                <i class="fa-solid {{ $portfolioGrossProfit >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1.5"></i>
                Margin: {{ $portfolioMarginPct }}% dari Nilai Kontrak
            </div>
        </div>

        <!-- Average Margin Rate -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Rata-rata Margin Laba Kotor</div>
            <div class="mt-2 text-2xl font-extrabold text-emerald-400">{{ $portfolioMarginPct }}%</div>
            <div class="mt-2 flex items-center text-xs text-slate-400">
                <i class="fa-solid fa-chart-pie mr-1.5"></i> Standar Target Industri: > 35%
            </div>
        </div>
    </div>

    <!-- Secondary Cost Breakdown Ribbon -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4 rounded-2xl bg-slate-950 border border-slate-800 text-xs">
        <div class="flex items-center justify-between px-3">
            <span class="text-slate-400">Biaya Tenaga Kerja (Labor):</span>
            <span class="font-mono font-bold text-white">Rp {{ number_format($portfolioLaborCost, 0, ',', '.') }}</span>
        </div>
        <div class="flex items-center justify-between px-3 border-t sm:border-t-0 sm:border-l border-slate-800 pt-2 sm:pt-0">
            <span class="text-slate-400">Beban Langsung (Direct):</span>
            <span class="font-mono font-bold text-cyan-400">Rp {{ number_format($portfolioDirectExpense, 0, ',', '.') }}</span>
        </div>
        <div class="flex items-center justify-between px-3 border-t lg:border-t-0 lg:border-l border-slate-800 pt-2 lg:pt-0">
            <span class="text-slate-400">Total Ditagihkan (Invoiced):</span>
            <span class="font-mono font-bold text-indigo-400">Rp {{ number_format($portfolioInvoiced, 0, ',', '.') }}</span>
        </div>
        <div class="flex items-center justify-between px-3 border-t lg:border-t-0 lg:border-l border-slate-800 pt-2 lg:pt-0">
            <span class="text-slate-400">Kas Masuk (Collected):</span>
            <span class="font-mono font-bold text-emerald-400">Rp {{ number_format($portfolioCollected, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm">
        <form method="GET" action="{{ route('profitability.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-emerald-500">
                    <option value="">-- Semua Status Pelaksanaan --</option>
                    <option value="planning" {{ request('status') === 'planning' ? 'selected' : '' }}>Perencanaan (Planning)</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Dalam Pengerjaan (In Progress)</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                </select>
            </div>
            <div>
                <select name="client_id" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-emerald-500">
                    <option value="">-- Semua Klien Korporasi --</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-semibold transition-all">
                    Filter Laba Rugi
                </button>
                @if(request()->anyFilled(['status', 'client_id']))
                    <a href="{{ route('profitability.index') }}" class="p-2.5 rounded-xl bg-slate-800/60 hover:bg-slate-800 text-slate-400 hover:text-white" title="Reset Filter">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Master Profitability Table -->
    <div class="rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs uppercase bg-slate-950/70 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Kode & Nama Proyek</th>
                        <th class="px-5 py-4 font-semibold">Klien & PM</th>
                        <th class="px-5 py-4 font-semibold text-right">Nilai Kontrak</th>
                        <th class="px-5 py-4 font-semibold text-right">Beban Tenaga Kerja</th>
                        <th class="px-5 py-4 font-semibold text-right">Beban Langsung</th>
                        <th class="px-5 py-4 font-semibold text-right">Total Biaya Riil</th>
                        <th class="px-5 py-4 font-semibold text-right">Laba Kotor (IDR)</th>
                        <th class="px-5 py-4 font-semibold text-center">Margin (%)</th>
                        <th class="px-5 py-4 font-semibold text-center">Status Anggaran</th>
                        <th class="px-5 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70">
                    @forelse($projects as $p)
                        <tr class="hover:bg-slate-850/40 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <a href="{{ route('profitability.show', $p->id) }}" class="font-mono text-xs font-bold text-indigo-400 hover:underline">
                                    {{ $p->code }}
                                </a>
                                <div class="font-bold text-white mt-0.5 max-w-xs truncate">{{ $p->name }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-medium text-slate-200">{{ $p->client_name }}</div>
                                <div class="text-xs text-slate-500">PM: {{ $p->pm_name }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right font-mono font-bold text-white">
                                Rp {{ number_format($p->contract_value, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right font-mono text-slate-300">
                                Rp {{ number_format($p->labor_cost, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right font-mono text-cyan-400">
                                Rp {{ number_format($p->direct_expense, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right font-mono font-bold text-white">
                                Rp {{ number_format($p->total_cost, 0, ',', '.') }}
                                <div class="text-[10px] text-slate-500">RAB: Rp {{ number_format($p->budget_cost, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right font-mono font-bold {{ $p->gross_profit >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                                Rp {{ number_format($p->gross_profit, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                @if($p->margin_percentage >= 40)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        {{ $p->margin_percentage }}%
                                    </span>
                                @elseif($p->margin_percentage >= 20)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-500/10 text-sky-400 border border-sky-500/20">
                                        {{ $p->margin_percentage }}%
                                    </span>
                                @elseif($p->margin_percentage > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        {{ $p->margin_percentage }}%
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        {{ $p->margin_percentage }}%
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                @if($p->cost_status === 'over_budget')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        <i class="fa-solid fa-triangle-exclamation mr-1 text-[9px]"></i> Over Budget ({{ $p->budget_burn_percentage }}%)
                                    </span>
                                @elseif($p->cost_status === 'near_limit')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        <i class="fa-solid fa-circle-exclamation mr-1 text-[9px]"></i> Siaga ({{ $p->budget_burn_percentage }}%)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <i class="fa-solid fa-circle-check mr-1 text-[9px]"></i> Aman ({{ $p->budget_burn_percentage }}%)
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('profitability.show', $p->id) }}" class="p-2 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 border border-indigo-500/30 transition-colors inline-block" title="Deep-dive Analisis Laba Rugi">
                                    <i class="fa-solid fa-chart-line"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-chart-pie text-3xl mb-3 text-slate-600 block"></i>
                                Belum ada data proyek untuk analisis profitabilitas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('page_title', 'Faktur & Piutang (AR)')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                <span class="w-2.5 h-6 bg-indigo-500 rounded-full inline-block"></span>
                Manajemen Faktur & Piutang Usaha (AR)
            </h1>
            <p class="text-sm text-slate-400 mt-1">Penagihan termin proyek, otomasi PPN 11% & PPh 23 Jasa, dan rekonsiliasi kas masuk.</p>
        </div>
        <a href="{{ route('invoices.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold shadow-lg shadow-indigo-600/30 transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Terbitkan Faktur Baru</span>
        </a>
    </div>

    <!-- AR Financial KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Outstanding AR -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Piutang Beredar (AR)</div>
            <div class="mt-2 text-2xl font-extrabold text-amber-400">Rp {{ number_format($totalOutstandingAR, 0, ',', '.') }}</div>
            <div class="mt-2 flex items-center text-xs text-amber-300 font-medium">
                <i class="fa-solid fa-hourglass-half mr-1.5"></i> Belum tertagih lunas
            </div>
        </div>

        <!-- Overdue AR -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Piutang Jatuh Tempo</div>
            <div class="mt-2 text-2xl font-extrabold text-rose-400">Rp {{ number_format($totalOverdueAR, 0, ',', '.') }}</div>
            <div class="mt-2 flex items-center text-xs text-rose-300 font-medium">
                <i class="fa-solid fa-triangle-exclamation mr-1.5"></i> Perlu follow-up kolektibilitas
            </div>
        </div>

        <!-- Collected Cash -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kas Masuk / Terbayar</div>
            <div class="mt-2 text-2xl font-extrabold text-emerald-400">Rp {{ number_format($totalCollected, 0, ',', '.') }}</div>
            <div class="mt-2 flex items-center text-xs text-emerald-300 font-medium">
                <i class="fa-solid fa-circle-check mr-1.5"></i> Terkonfirmasi di rekening bank
            </div>
        </div>

        <!-- Total Invoiced -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Nilai Faktur Diterbitkan</div>
            <div class="mt-2 text-2xl font-extrabold text-indigo-400">Rp {{ number_format($totalInvoicedAmount, 0, ',', '.') }}</div>
            <div class="mt-2 flex items-center text-xs text-slate-400">
                <i class="fa-solid fa-file-invoice-dollar mr-1.5"></i> Termasuk PPN & potong PPh
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm">
        <form method="GET" action="{{ route('invoices.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Faktur / Proyek / Klien..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-indigo-500">
            </div>
            <div>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                    <option value="">-- Semua Status Faktur --</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft (Konsep)</option>
                    <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Terkirim / Belum Bayar</option>
                    <option value="partially_paid" {{ request('status') === 'partially_paid' ? 'selected' : '' }}>Dibayar Sebagian</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Jatuh Tempo (Overdue)</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div>
                <select name="client_id" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                    <option value="">-- Semua Klien --</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-semibold transition-all">
                    Filter Data
                </button>
                @if(request()->anyFilled(['status', 'client_id', 'search']))
                    <a href="{{ route('invoices.index') }}" class="p-2.5 rounded-xl bg-slate-800/60 hover:bg-slate-800 text-slate-400 hover:text-white" title="Reset Filter">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Invoices List Table -->
    <div class="rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs uppercase bg-slate-950/70 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Nomor & Tanggal</th>
                        <th class="px-5 py-4 font-semibold">Klien & Proyek</th>
                        <th class="px-5 py-4 font-semibold">Jatuh Tempo</th>
                        <th class="px-5 py-4 font-semibold text-right">Nilai Faktur</th>
                        <th class="px-5 py-4 font-semibold text-right">Sisa Piutang</th>
                        <th class="px-5 py-4 font-semibold text-center">Status</th>
                        <th class="px-5 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-slate-850/40 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <a href="{{ route('invoices.show', $inv->id) }}" class="font-mono text-sm font-bold text-indigo-400 hover:underline">
                                    {{ $inv->invoice_number }}
                                </a>
                                <div class="text-xs text-slate-500 mt-0.5">Terbit: {{ $inv->issue_date->format('d M Y') }}</div>
                            </td>
                            <td class="px-5 py-4 max-w-xs">
                                <div class="font-semibold text-white truncate">{{ $inv->client->company_name }}</div>
                                <div class="text-xs text-slate-400 truncate mt-0.5">
                                    <a href="{{ route('projects.show', $inv->project_id) }}" class="hover:text-indigo-300">
                                        {{ $inv->project->name }}
                                    </a>
                                </div>
                                @if($inv->milestone)
                                    <span class="inline-flex items-center gap-1 text-[10px] text-cyan-400 mt-1 font-medium">
                                        <i class="fa-solid fa-flag-checkered text-[9px]"></i> {{ $inv->milestone->title }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-medium text-slate-300">{{ $inv->due_date->format('d M Y') }}</div>
                                @php
                                    $diff = now()->startOfDay()->diffInDays($inv->due_date, false);
                                @endphp
                                @if($inv->status !== 'paid' && $inv->status !== 'cancelled')
                                    @if($diff < 0)
                                        <div class="text-[11px] text-rose-400 font-bold">Terlambat {{ abs($diff) }} hari</div>
                                    @elseif($diff == 0)
                                        <div class="text-[11px] text-amber-400 font-bold">Jatuh tempo hari ini</div>
                                    @else
                                        <div class="text-[11px] text-slate-500">{{ $diff }} hari lagi</div>
                                    @endif
                                @else
                                    <div class="text-[11px] text-slate-500">{{ $inv->payment_terms }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right font-bold text-white">
                                Rp {{ number_format($inv->total_amount, 0, ',', '.') }}
                                <div class="text-[11px] text-slate-500 font-normal">DPP: Rp {{ number_format($inv->subtotal, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <div class="font-bold {{ $inv->remaining_balance > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                                    Rp {{ number_format($inv->remaining_balance, 0, ',', '.') }}
                                </div>
                                @php
                                    $pct = $inv->total_amount > 0 ? min(100, round(($inv->paid_amount / $inv->total_amount) * 100)) : 0;
                                @endphp
                                <div class="w-24 ml-auto mt-1 bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <div class="text-[10px] text-slate-500 mt-0.5">{{ $pct }}% lunas</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                {!! $inv->status_badge !!}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right space-x-1">
                                <!-- Print / PDF -->
                                <a href="{{ route('invoices.print', $inv->id) }}" target="_blank" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors inline-block" title="Cetak Faktur (Print / PDF)">
                                    <i class="fa-solid fa-print"></i>
                                </a>

                                <!-- Show Details -->
                                <a href="{{ route('invoices.show', $inv->id) }}" class="p-2 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 border border-indigo-500/30 transition-colors inline-block" title="Detail & Pelunasan AR">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-file-invoice text-3xl mb-3 text-slate-600 block"></i>
                                Belum ada faktur penagihan yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="px-6 py-4 border-t border-slate-800">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

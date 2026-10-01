@extends('layouts.app')

@section('title', 'Master Klien Korporasi')
@section('page_title', 'Master Data Klien (CRM)')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-800/80">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Klien & Mitra Korporasi</h1>
            <p class="text-sm text-slate-400 mt-1">Kelola data legalitas perusahaan mitra, PIC penanggung jawab, dan plafon kredit.</p>
        </div>
        <a href="{{ route('clients.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>+ Daftarkan Klien Baru</span>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Klien</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['total'] }} <span class="text-xs font-normal text-slate-400">Perusahaan</span></div>
        </div>
        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status Aktif</span>
            <div class="text-2xl font-black text-emerald-400 mt-1">{{ $stats['active'] }} <span class="text-xs font-normal text-slate-400">Aktif Berjalan</span></div>
        </div>
        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sektor Banking & Finansial</span>
            <div class="text-2xl font-black text-violet-400 mt-1">{{ $stats['banking'] }} <span class="text-xs font-normal text-slate-400">Klien</span></div>
        </div>
        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Plafon Kredit</span>
            <div class="text-xl font-black text-white mt-1 truncate">Rp {{ number_format($stats['total_credit'], 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="p-4 rounded-xl bg-slate-900/70 border border-slate-800">
        <form method="GET" action="{{ route('clients.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama perusahaan, kode klien, industri..." 
                       class="w-full pl-10 pr-4 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
            </div>

            <div class="flex gap-2">
                <select name="status" class="px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-sm text-slate-300 focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-sm font-semibold transition-all">
                    Filter
                </button>
                @if($search || $status)
                    <a href="{{ route('clients.index') }}" class="px-3 py-2 bg-slate-800/50 hover:bg-slate-800 text-slate-400 hover:text-white rounded-lg text-sm transition-all flex items-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Kode & Perusahaan</th>
                        <th class="px-6 py-4">Industri & Kota</th>
                        <th class="px-6 py-4">PIC Utama</th>
                        <th class="px-6 py-4">Proyek Aktif</th>
                        <th class="px-6 py-4">Termin Bayar</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($clients as $c)
                        <tr class="hover:bg-slate-800/30 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-slate-800 text-slate-300 border border-slate-700/60 inline-block mb-1">{{ $c->client_code }}</span>
                                <div class="font-bold text-white group-hover:text-indigo-400 transition-colors">
                                    <a href="{{ route('clients.show', $c) }}">{{ $c->company_name }}</a>
                                </div>
                                @if($c->tax_id)
                                    <span class="text-[10px] text-slate-400">NPWP: {{ $c->tax_id }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-200 font-medium">{{ $c->industry ?? '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $c->city ?? 'Indonesia' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($c->primaryContact)
                                    <div class="text-white font-medium">{{ $c->primaryContact->name }}</div>
                                    <div class="text-xs text-indigo-400">{{ $c->primaryContact->position ?? 'PIC' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $c->primaryContact->phone ?? $c->phone }}</div>
                                @else
                                    <span class="text-xs text-slate-400">Belum ada PIC</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                                    {{ $c->projects->count() }} Proyek
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-300">
                                Net {{ $c->payment_terms_days }} Hari
                                <span class="block text-[10px] text-slate-400">Plafon: Rp {{ number_format($c->credit_limit, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($c->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-800 text-slate-400 border border-slate-700">
                                        Non-Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('clients.show', $c) }}" title="Lihat 360 Klien" class="p-2 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('clients.edit', $c) }}" title="Edit Klien" class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Tidak ada data klien yang cocok dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($clients->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $clients->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

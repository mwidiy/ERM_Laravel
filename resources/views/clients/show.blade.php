@extends('layouts.app')

@section('title', $client->company_name)
@section('page_title', 'Profil Klien 360: ' . $client->company_name)

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Action Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-800">
        <div class="flex items-center gap-3">
            <a href="{{ route('clients.index') }}" class="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold px-2 py-0.5 rounded bg-slate-800 text-indigo-400 border border-slate-700/60 font-mono">{{ $client->client_code }}</span>
                    @if($client->status === 'active')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Aktif</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-800 text-slate-400 border border-slate-700">Non-Aktif</span>
                    @endif
                </div>
                <h1 class="text-2xl font-black text-white tracking-tight mt-1">{{ $client->company_name }}</h1>
                <p class="text-xs text-slate-400">{{ $client->industry ?? 'Perusahaan Korporasi' }} • {{ $client->city ?? 'Indonesia' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('projects.create') }}?client_id={{ $client->id }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                <span>+ Buat Proyek untuk Klien Ini</span>
            </a>
            <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 text-xs font-semibold border border-slate-700/80 transition-all">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                <span>Edit Klien</span>
            </a>
        </div>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-lg">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Nilai Proyek</span>
            <div class="text-xl font-black text-white mt-1">Rp {{ number_format($totalProjectValue, 0, ',', '.') }}</div>
            <span class="text-[11px] text-indigo-400 mt-1 block">Akumulasi seluruh kontrak</span>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-lg">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Proyek Berjalan</span>
            <div class="text-2xl font-black text-sky-400 mt-1">{{ $activeProjects }} <span class="text-xs font-normal text-slate-400">Proyek Aktif</span></div>
            <span class="text-[11px] text-slate-400 mt-1 block">Total portofolio: {{ $client->projects->count() }}</span>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-lg">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Termin Pembayaran</span>
            <div class="text-2xl font-black text-emerald-400 mt-1">Net {{ $client->payment_terms_days }} Hari</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Jatuh tempo faktur resmi</span>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900/90 border border-slate-800 shadow-lg">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Plafon Kredit Terbuka</span>
            <div class="text-xl font-black text-white mt-1">Rp {{ number_format($client->credit_limit, 0, ',', '.') }}</div>
            <span class="text-[11px] text-slate-400 mt-1 block">Limit maksimal piutang</span>
        </div>
    </div>

    <!-- 2 Column Layout: Project Portfolio & Client Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Projects History (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-white tracking-tight">Daftar Proyek Klien Ini</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Seluruh kontrak kerja sama yang sedang atau telah diselesaikan</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700">
                        {{ $client->projects->count() }} Proyek
                    </span>
                </div>

                <div class="divide-y divide-slate-800/60">
                    @forelse($client->projects as $prj)
                        <div class="p-5 hover:bg-slate-800/20 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="space-y-1.5 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono font-bold px-2 py-0.5 rounded bg-slate-800 text-indigo-400 border border-slate-700/60">{{ $prj->project_code }}</span>
                                    <span class="text-xs text-slate-400 uppercase tracking-wider">{{ str_replace('_', ' ', $prj->contract_type) }}</span>
                                </div>
                                <h3 class="font-bold text-white text-base hover:text-indigo-400 transition-colors">
                                    <a href="{{ route('projects.show', $prj) }}">{{ $prj->name }}</a>
                                </h3>
                                <div class="flex items-center gap-4 text-xs text-slate-400">
                                    <span>PM: <strong class="text-slate-200">{{ $prj->projectManager->name ?? 'Belum ditentukan' }}</strong></span>
                                    <span>Periode: {{ $prj->start_date ? $prj->start_date->format('d M Y') : '-' }} s/d {{ $prj->end_date ? $prj->end_date->format('d M Y') : 'Ongoing' }}</span>
                                </div>
                            </div>

                            <div class="flex items-center sm:flex-col sm:items-end justify-between gap-2 shrink-0">
                                <div class="text-sm font-black text-white">
                                    Rp {{ number_format($prj->contract_value, 0, ',', '.') }}
                                </div>
                                <div>
                                    @php
                                        $badgeClass = match($prj->status) {
                                            'in_progress' => 'bg-sky-500/10 text-sky-400 border-sky-500/30',
                                            'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                            'planning' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/30',
                                            'on_hold' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                                            default => 'bg-slate-800 text-slate-400 border-slate-700'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $prj->status)) }}
                                    </span>
                                </div>
                                <a href="{{ route('projects.show', $prj) }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition-colors mt-1">
                                    Detail Proyek &rarr;
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-sm">
                            Belum ada proyek yang terdaftar untuk klien ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column: Legal Details & Contacts (1/3) -->
        <div class="space-y-6">
            
            <!-- Legal & Info Card -->
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 shadow-xl space-y-4">
                <h3 class="font-bold text-white text-base border-b border-slate-800 pb-3">Legalitas & Informasi Kontak</h3>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Nomor NPWP</span>
                        <span class="text-slate-200 font-mono font-medium">{{ $client->tax_id ?? 'Tidak tercatat' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Email Perusahaan</span>
                        <span class="text-slate-200">{{ $client->email ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Telepon Kantor</span>
                        <span class="text-slate-200">{{ $client->phone ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Website Resmi</span>
                        @if($client->website)
                            <a href="{{ $client->website }}" target="_blank" class="text-indigo-400 hover:underline">{{ $client->website }}</a>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Alamat Lengkap</span>
                        <p class="text-slate-300 mt-0.5 leading-relaxed">{{ $client->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- PIC Contacts Card -->
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 shadow-xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <h3 class="font-bold text-white text-base">PIC & Pejabat Berwenang</h3>
                </div>

                <div class="space-y-3">
                    @forelse($client->contacts as $contact)
                        <div class="p-3 rounded-xl bg-slate-950/60 border border-slate-800/80 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-white text-sm">{{ $contact->name }}</span>
                                @if($contact->is_primary)
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">Utama</span>
                                @endif
                            </div>
                            <div class="text-xs text-indigo-400 font-medium">{{ $contact->position ?? 'PIC' }}</div>
                            <div class="text-xs text-slate-400 flex flex-col gap-0.5 pt-1">
                                <span>✉️ {{ $contact->email ?? '-' }}</span>
                                <span>📱 {{ $contact->phone ?? '-' }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada data kontak PIC.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

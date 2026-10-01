@extends('layouts.app')

@section('title', 'Manajemen Portofolio Proyek')
@section('page_title', 'Portofolio & WBS Proyek')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-800">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Portofolio & Delivery Proyek</h1>
            <p class="text-sm text-slate-400 mt-1">Kelola seluruh kontrak jasa, jadwal tahapan milestone termin, dan alokasi tim konsultan.</p>
        </div>
        @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
        <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            <span>+ Buat Proyek Baru</span>
        </a>
        @endif
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nilai Total Portofolio</span>
            <div class="text-xl font-black text-white mt-1">Rp {{ number_format($stats['total_value'], 0, ',', '.') }}</div>
            <span class="text-[11px] text-indigo-400 mt-1 block">Seluruh proyek aktif</span>
        </div>
        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sedang Berjalan (In Progress)</span>
            <div class="text-2xl font-black text-sky-400 mt-1">{{ $stats['in_progress'] }} <span class="text-xs font-normal text-slate-400">Proyek</span></div>
            <span class="text-[11px] text-sky-400 mt-1 block">Dalam tahap pelaksanaan</span>
        </div>
        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tahap Perencanaan (Planning)</span>
            <div class="text-2xl font-black text-indigo-400 mt-1">{{ $stats['planning'] }} <span class="text-xs font-normal text-slate-400">Proyek</span></div>
            <span class="text-[11px] text-indigo-400 mt-1 block">Persiapan kickoff</span>
        </div>
        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Selesai (Completed)</span>
            <div class="text-2xl font-black text-emerald-400 mt-1">{{ $stats['completed'] }} <span class="text-xs font-normal text-slate-400">Proyek</span></div>
            <span class="text-[11px] text-emerald-400 mt-1 block">Telah serah terima (BAST)</span>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="p-4 rounded-xl bg-slate-900/70 border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <!-- Status Filter Pills -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0">
            <a href="{{ route('projects.index') }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-all {{ empty($status) ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                Semua Proyek
            </a>
            <a href="{{ route('projects.index', ['status' => 'in_progress']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-all {{ $status === 'in_progress' ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                In Progress ({{ $stats['in_progress'] }})
            </a>
            <a href="{{ route('projects.index', ['status' => 'planning']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-all {{ $status === 'planning' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                Planning ({{ $stats['planning'] }})
            </a>
            <a href="{{ route('projects.index', ['status' => 'completed']) }}" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-all {{ $status === 'completed' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                Completed ({{ $stats['completed'] }})
            </a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('projects.index') }}" class="flex items-center gap-2">
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama proyek, kode, klien..." 
                       class="w-full pl-9 pr-4 py-1.5 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                <svg class="w-4 h-4 text-slate-500 absolute left-3 top-2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-semibold">
                Cari
            </button>
        </form>
    </div>

    <!-- Projects Grid View -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $prj)
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 hover:border-slate-700 transition-all p-6 flex flex-col justify-between shadow-xl group">
                
                <!-- Card Header -->
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="text-xs font-mono font-bold px-2 py-0.5 rounded bg-slate-800 text-indigo-400 border border-slate-700/60">{{ $prj->project_code }}</span>
                        
                        <div class="flex items-center gap-1.5">
                            @if($prj->priority === 'urgent')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-500/10 text-rose-400 border border-rose-500/30">Urgent</span>
                            @elseif($prj->priority === 'high')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-500/10 text-amber-400 border border-amber-500/30">High</span>
                            @endif

                            @php
                                $statusBadge = match($prj->status) {
                                    'in_progress' => 'bg-sky-500/10 text-sky-400 border-sky-500/30',
                                    'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                    'planning' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/30',
                                    'on_hold' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                                    default => 'bg-slate-800 text-slate-400 border-slate-700'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $statusBadge }}">
                                {{ ucfirst(str_replace('_', ' ', $prj->status)) }}
                            </span>
                        </div>
                    </div>

                    <h2 class="text-base font-bold text-white group-hover:text-indigo-400 transition-colors line-clamp-2">
                        <a href="{{ route('projects.show', $prj) }}">{{ $prj->name }}</a>
                    </h2>

                    <div class="text-xs text-slate-400 mt-1 mb-4 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="truncate font-medium text-slate-300">{{ $prj->client->company_name ?? 'Klien Internal' }}</span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-1.5 mb-5 p-3 rounded-xl bg-slate-950/60 border border-slate-800/80">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-400">Progres Fisik</span>
                            <span class="font-bold text-white">{{ $prj->progress_percentage }}%</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-sky-400 transition-all duration-500" style="width: {{ $prj->progress_percentage }}%"></div>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-400 pt-1">
                            <span>Mulai: {{ $prj->start_date ? $prj->start_date->format('d/m/y') : '-' }}</span>
                            <span>Target: {{ $prj->end_date ? $prj->end_date->format('d/m/y') : 'Ongoing' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card Footer & Financial Details -->
                <div class="pt-4 border-t border-slate-800/80 space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Nilai Kontrak</span>
                            <span class="text-sm font-black text-white">Rp {{ number_format($prj->contract_value, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Project Manager</span>
                            <span class="text-xs font-semibold text-slate-200">{{ $prj->projectManager->name ?? 'Belum Di-assign' }}</span>
                        </div>
                    </div>

                    <a href="{{ route('projects.show', $prj) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-indigo-600 text-slate-200 hover:text-white text-xs font-bold transition-all shadow-sm">
                        <span>Buka Workspace Proyek</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>

            </div>
        @empty
            <div class="col-span-full py-16 text-center text-slate-400 bg-slate-900/40 rounded-2xl border border-slate-800">
                <svg class="w-12 h-12 mx-auto text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                </svg>
                <p class="text-base font-medium text-white">Tidak ada data proyek yang ditemukan</p>
                <p class="text-xs text-slate-400 mt-1">Gunakan tombol "+ Buat Proyek Baru" untuk mendaftarkan proyek perdana Anda.</p>
            </div>
        @endforelse
    </div>

    @if($projects->hasPages())
        <div class="p-4 border-t border-slate-800">
            {{ $projects->links() }}
        </div>
    @endif

</div>
@endsection

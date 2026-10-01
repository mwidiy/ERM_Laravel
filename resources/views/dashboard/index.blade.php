@extends('layouts.app')

@section('title', 'Executive Dashboard')
@section('page_title', 'Executive Overview & KPI')

@section('content')
<div class="space-y-8">
    
    <!-- Hero Header with Greeting -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2 border-b border-slate-800/80">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                Executive Project Dashboard
            </h1>
            <p class="text-sm text-slate-400 mt-1">
                Ikhtisar kinerja portofolio proyek, jadwal termin penagihan, dan utilisasi tim secara terpadu.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-900 border border-slate-800 text-xs text-slate-300 font-medium">
                <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ now()->translatedFormat('l, d F Y') }}
            </span>
            @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white text-xs font-bold shadow-lg shadow-indigo-500/25 transition-all">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Buat Proyek</span>
            </a>
            @endif
        </div>
    </div>

    <!-- 4 Main Executive Metric KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Metric 1: Total Nilai Kontrak Aktif -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 to-slate-950 p-6 border border-slate-800 shadow-xl group hover:border-indigo-500/50 transition-all">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-all"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Nilai Kontrak</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-white tracking-tight">
                    Rp {{ number_format($totalContractValue, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-xs text-emerald-400 font-medium">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span>Portofolio Aktif Korporasi</span>
                </div>
            </div>
        </div>

        <!-- Metric 2: Proyek Berjalan -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 to-slate-950 p-6 border border-slate-800 shadow-xl group hover:border-sky-500/50 transition-all">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-sky-500/10 rounded-full blur-2xl group-hover:bg-sky-500/20 transition-all"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Proyek Berjalan</span>
                <div class="w-10 h-10 rounded-xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-white tracking-tight">
                    {{ $activeProjectsCount }} <span class="text-sm font-normal text-slate-400">Proyek Aktif</span>
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-xs text-sky-400 font-medium">
                    <span class="w-2 h-2 rounded-full bg-sky-400 animate-ping"></span>
                    <span>Dalam Tahap Delivery</span>
                </div>
            </div>
        </div>

        <!-- Metric 3: Klien Korporasi Aktif -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 to-slate-950 p-6 border border-slate-800 shadow-xl group hover:border-violet-500/50 transition-all">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-violet-500/10 rounded-full blur-2xl group-hover:bg-violet-500/20 transition-all"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Klien Korporasi</span>
                <div class="w-10 h-10 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-violet-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-white tracking-tight">
                    {{ $totalClientsCount }} <span class="text-sm font-normal text-slate-400">Enterprise</span>
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-xs text-violet-400 font-medium">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Mitra Strategis Bisnis</span>
                </div>
            </div>
        </div>

        <!-- Metric 4: Termin Terpenuhi / Ditagih -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 to-slate-950 p-6 border border-slate-800 shadow-xl group hover:border-emerald-500/50 transition-all">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Termin Siap / Terfaktur</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-black text-white tracking-tight">
                    Rp {{ number_format($invoicedOrReadyMilestones, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-1.5 mt-2 text-xs text-emerald-400 font-medium">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Potensi Arus Kas Masuk</span>
                </div>
            </div>
        </div>

    </div>

    @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'finance', 'project_manager']))
    <!-- Phase 3: AR & Financial Health Summary Strip (Management & Finance) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4 rounded-2xl bg-gradient-to-r from-slate-900/90 via-indigo-950/20 to-slate-900/90 border border-slate-800 shadow-lg">
        <div class="flex items-center gap-3.5 px-2">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400 shrink-0">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Piutang Beredar (AR)</div>
                <div class="text-lg font-black text-amber-400">Rp {{ number_format($totalOutstandingAR, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="flex items-center gap-3.5 px-2 border-t sm:border-t-0 sm:border-l border-slate-800 pt-2 sm:pt-0">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kas Masuk (Collected)</div>
                <div class="text-lg font-black text-emerald-400">Rp {{ number_format($totalCollectedCash, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="flex items-center gap-3.5 px-2 border-t lg:border-t-0 lg:border-l border-slate-800 pt-2 lg:pt-0">
            <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 shrink-0">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Beban Langsung Proyek</div>
                <div class="text-lg font-black text-cyan-400">Rp {{ number_format($totalDirectExpenses, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="flex items-center gap-3.5 px-2 border-t lg:border-t-0 lg:border-l border-slate-800 pt-2 lg:pt-0">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">
                <i class="fa-solid fa-user-clock"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Biaya Tenaga Kerja (Labor)</div>
                <div class="text-lg font-black text-white">Rp {{ number_format($totalLaborCost, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    @else
    <!-- Consultant Delivery & Timesheet KPI Strip -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4 rounded-2xl bg-gradient-to-r from-slate-900/90 via-indigo-950/20 to-slate-900/90 border border-slate-800 shadow-lg">
        <div class="flex items-center gap-3.5 px-2">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Tugas Aktif WBS Saya</div>
                <div class="text-lg font-black text-indigo-400">{{ $myTasks->count() }} Tugas</div>
            </div>
        </div>

        <div class="flex items-center gap-3.5 px-2 border-t sm:border-t-0 sm:border-l border-slate-800 pt-2 sm:pt-0">
            <div class="w-10 h-10 rounded-xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-400 shrink-0">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Jam Kerja Bulan Ini</div>
                <div class="text-lg font-black text-sky-400">{{ number_format($myHoursThisMonth, 1) }} Jam</div>
            </div>
        </div>

        <div class="flex items-center gap-3.5 px-2 border-t lg:border-t-0 lg:border-l border-slate-800 pt-2 lg:pt-0">
            <div class="w-10 h-10 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center text-violet-400 shrink-0">
                <i class="fa-solid fa-laptop-code"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Spesialisasi Jabatan</div>
                <div class="text-xs font-bold text-white truncate max-w-[180px]">{{ Auth::user()->position ?? 'Consultant' }}</div>
            </div>
        </div>

        <div class="flex items-center gap-3.5 px-2 border-t lg:border-t-0 lg:border-l border-slate-800 pt-2 lg:pt-0">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Status Personil</div>
                <div class="text-xs font-bold text-emerald-400">Aktif Terverifikasi</div>
            </div>
        </div>
    </div>
    @endif

    <!-- 2 Column Master Grid: Projects Table & Side Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Portofolio Proyek Terkini (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-white tracking-tight">Portofolio Proyek Terkini</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Pemantauan progres pelaksanaan dan nilai kontrak per klien</p>
                    </div>
                    <a href="{{ route('projects.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition-colors flex items-center gap-1">
                        <span>Lihat Semua Proyek</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="px-6 py-4">Proyek & Klien</th>
                                <th class="px-6 py-4">Progress Delivery</th>
                                <th class="px-6 py-4">Nilai Kontrak</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($recentProjects as $prj)
                                <tr class="hover:bg-slate-800/30 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-white group-hover:text-indigo-400 transition-colors">
                                            <a href="{{ route('projects.show', $prj) }}">{{ $prj->name }}</a>
                                        </div>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-slate-800 text-slate-300 border border-slate-700/60">{{ $prj->project_code }}</span>
                                            <span class="text-xs text-slate-400 truncate max-w-[200px]">{{ $prj->client->company_name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="w-44">
                                            <div class="flex items-center justify-between text-xs mb-1.5">
                                                <span class="font-semibold text-slate-300">{{ $prj->progress_percentage }}% Selesai</span>
                                                <span class="text-[10px] text-slate-400">Target: {{ $prj->end_date ? $prj->end_date->format('M Y') : 'Ongoing' }}</span>
                                            </div>
                                            <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                                                <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-sky-400 transition-all duration-500" style="width: {{ $prj->progress_percentage }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-white">
                                        Rp {{ number_format($prj->contract_value, 0, ',', '.') }}
                                        <span class="block text-[10px] font-normal text-slate-400 uppercase tracking-wider">{{ str_replace('_', ' ', $prj->contract_type) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $badgeClass = match($prj->status) {
                                                'in_progress' => 'bg-sky-500/10 text-sky-400 border-sky-500/30',
                                                'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                                'planning' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/30',
                                                'on_hold' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                                                default => 'bg-slate-800 text-slate-400 border-slate-700'
                                            };
                                            $label = match($prj->status) {
                                                'in_progress' => 'In Progress',
                                                'completed' => 'Completed',
                                                'planning' => 'Planning',
                                                'on_hold' => 'On Hold',
                                                default => ucfirst($prj->status)
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('projects.show', $prj) }}" class="inline-flex items-center justify-center p-2 rounded-lg bg-slate-800 hover:bg-indigo-600 text-slate-300 hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                                        Belum ada data proyek. Silakan buat proyek baru pertama Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Upcoming Milestones & Billing Schedule -->
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-white tracking-tight">Jadwal Termin & Milestone Mendatang</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Syarat penagihan termin faktur berdasarkan deliverable proyek</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                        {{ $upcomingMilestones->count() }} Termin Pending
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($upcomingMilestones as $m)
                        <div class="p-4 rounded-xl bg-slate-950/60 border border-slate-800/80 hover:border-slate-700 transition-colors">
                            <div class="flex items-start justify-between gap-2">
                                <span class="text-xs font-bold text-indigo-400 tracking-wider uppercase">{{ $m->project->project_code ?? 'PRJ' }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                    {{ $m->status === 'in_progress' ? 'Dalam Pengerjaan' : 'Menunggu' }}
                                </span>
                            </div>
                            <h3 class="font-bold text-white text-sm mt-2 line-clamp-1">{{ $m->title }}</h3>
                            <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $m->description }}</p>
                            
                            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                                <div>
                                    <span class="text-slate-400 block text-[10px]">Nilai Termin:</span>
                                    <span class="font-bold text-emerald-400">Rp {{ number_format($m->billing_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-slate-400 block text-[10px]">Target Selesai:</span>
                                    <span class="font-medium text-slate-300">{{ $m->due_date ? $m->due_date->format('d M Y') : 'TBD' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-6 text-center text-slate-400 text-xs">
                            Tidak ada jadwal termin terdekat yang pending.
                        </div>
                    @endforelse
                </div>
            </div>

            @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'finance', 'project_manager']))
            <!-- Recent Invoices & AR Ledger -->
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                            <i class="fa-solid fa-file-invoice-dollar text-indigo-400 text-base"></i>
                            Faktur Penagihan Terkini (AR Ledger)
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Status invoice termin klien, PPN 11%, dan piutang berjalan</p>
                    </div>
                    <a href="{{ route('invoices.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition-colors flex items-center gap-1">
                        <span>Buka Modul Faktur</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="px-6 py-3.5">No Faktur & Tanggal</th>
                                <th class="px-6 py-3.5">Klien & Proyek</th>
                                <th class="px-6 py-3.5 text-right">Nilai Faktur</th>
                                <th class="px-6 py-3.5 text-right">Sisa Piutang</th>
                                <th class="px-6 py-3.5 text-center">Status</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($recentInvoices as $inv)
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('invoices.show', $inv->id) }}" class="font-mono text-xs font-bold text-indigo-400 hover:underline">
                                            {{ $inv->invoice_number }}
                                        </a>
                                        <div class="text-[11px] text-slate-500 mt-0.5">{{ $inv->issue_date->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-white truncate max-w-xs">{{ $inv->client->company_name }}</div>
                                        <div class="text-[11px] text-slate-400 truncate max-w-xs mt-0.5">{{ $inv->project->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-white whitespace-nowrap">
                                        Rp {{ number_format($inv->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold whitespace-nowrap {{ $inv->remaining_balance > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                                        Rp {{ number_format($inv->remaining_balance, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        {!! $inv->status_badge !!}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                                        <a href="{{ route('invoices.print', $inv->id) }}" target="_blank" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white inline-block text-xs" title="Cetak Faktur">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                        <a href="{{ route('invoices.show', $inv->id) }}" class="p-1.5 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 border border-indigo-500/30 inline-block text-xs" title="Detail Tagihan">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-6 text-center text-slate-500 text-xs">
                                        Belum ada faktur penagihan yang diterbitkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <!-- My Assigned Tasks (Personal Workspace for Consultants) -->
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-indigo-400 text-base"></i>
                            Tugas WBS Yang Ditugaskan Kepada Anda
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar tugas pelaksanaan teknis yang perlu Anda selesaikan</p>
                    </div>
                    <a href="{{ route('tasks.index') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition-colors flex items-center gap-1">
                        <span>Buka Papan Kanban</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="px-6 py-3.5">Judul Tugas & Proyek</th>
                                <th class="px-6 py-3.5">Target Selesai</th>
                                <th class="px-6 py-3.5 text-center">Prioritas</th>
                                <th class="px-6 py-3.5 text-center">Status</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($myTasks as $task)
                                <tr class="hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-white">{{ $task->title }}</div>
                                        <div class="text-[11px] text-indigo-400 mt-0.5">{{ $task->project->project_code ?? 'PRJ' }} - {{ $task->project->name ?? 'Proyek' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-300">
                                        {{ $task->due_date ? $task->due_date->format('d M Y') : 'Tanpa Deadline' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $prioClass = match($task->priority) {
                                                'urgent' => 'bg-rose-500/10 text-rose-400 border-rose-500/30',
                                                'high' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                                                'medium' => 'bg-sky-500/10 text-sky-400 border-sky-500/30',
                                                default => 'bg-slate-800 text-slate-400 border-slate-700'
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $prioClass }}">
                                            {{ $task->priority }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('tasks.index') }}" class="px-3 py-1 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/40 text-indigo-300 border border-indigo-500/30 text-xs font-semibold transition-all">
                                            Buka Task
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500 text-xs">
                                        Tidak ada tugas WBS aktif yang ditugaskan kepada Anda saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>

        <!-- Right Column: Status Summary, Quick Actions & Team (1/3 width) -->
        <div class="space-y-6">
            
            <!-- Quick Actions Panel Based on Role -->
            <div class="rounded-2xl bg-gradient-to-br from-indigo-950/40 via-slate-900 to-slate-950 border border-indigo-500/30 p-6 shadow-xl">
                <h3 class="font-bold text-white text-base mb-1">Aksi Cepat Berdasarkan Jobdesk</h3>
                <p class="text-xs text-slate-400 mb-5">Pintasan modul sesuai hak akses wewenang Anda.</p>

                <div class="space-y-2.5">
                    @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
                    <a href="{{ route('projects.create') }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-900/90 hover:bg-indigo-600/30 border border-slate-800 hover:border-indigo-500/40 text-slate-200 hover:text-white transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            </div>
                            <span class="text-xs font-semibold">Buat Proyek Baru</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="{{ route('clients.create') }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-900/90 hover:bg-violet-600/30 border border-slate-800 hover:border-violet-500/40 text-slate-200 hover:text-white transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-violet-500/20 text-violet-400 flex items-center justify-center group-hover:bg-violet-500 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                            </div>
                            <span class="text-xs font-semibold">Registrasi Klien Baru</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    @endif

                    @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'finance']))
                    <a href="{{ route('services.index') }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-900/90 hover:bg-sky-600/30 border border-slate-800 hover:border-sky-500/40 text-slate-200 hover:text-white transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-sky-500/20 text-sky-400 flex items-center justify-center group-hover:bg-sky-500 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <span class="text-xs font-semibold">Katalog & Rate Cards Layanan</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    @endif

                    @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
                    <a href="{{ route('team.index') }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-900/90 hover:bg-emerald-600/30 border border-slate-800 hover:border-emerald-500/40 text-slate-200 hover:text-white transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                            <span class="text-xs font-semibold">Alokasi & Tarif Tim Internal</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    @endif

                    <!-- Quick actions always available for Consultant & Team -->
                    <a href="{{ route('timesheets.index') }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-900/90 hover:bg-emerald-600/30 border border-slate-800 hover:border-emerald-500/40 text-slate-200 hover:text-white transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-clock text-xs"></i>
                            </div>
                            <span class="text-xs font-semibold">Catat Log Jam Kerja (Timesheet)</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="{{ route('tasks.index') }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-900/90 hover:bg-indigo-600/30 border border-slate-800 hover:border-indigo-500/40 text-slate-200 hover:text-white transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-list-check text-xs"></i>
                            </div>
                            <span class="text-xs font-semibold">Papan Tugas WBS (Kanban)</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>

                    <a href="{{ route('expenses.index') }}" class="w-full flex items-center justify-between p-3 rounded-xl bg-slate-900/90 hover:bg-amber-600/30 border border-slate-800 hover:border-amber-500/40 text-slate-200 hover:text-white transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors">
                                <i class="fa-solid fa-receipt text-xs"></i>
                            </div>
                            <span class="text-xs font-semibold">Ajukan Klaim Beban Proyek</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>

            <!-- Project Distribution by Status -->
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 shadow-xl">
                <h3 class="font-bold text-white text-base mb-1">Status Siklus Proyek</h3>
                <p class="text-xs text-slate-400 mb-5">Distribusi tahapan pelaksanaan saat ini</p>

                <div class="space-y-3.5">
                    <div>
                        <div class="flex justify-between text-xs font-medium mb-1">
                            <span class="text-sky-400">Dalam Pengerjaan (In Progress)</span>
                            <span class="text-white font-bold">{{ $statusCounts['in_progress'] }}</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-800">
                            <div class="h-full rounded-full bg-sky-500" style="width: {{ $activeProjectsCount > 0 ? ($statusCounts['in_progress'] / max($activeProjectsCount, 1)) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-medium mb-1">
                            <span class="text-indigo-400">Tahap Perencanaan (Planning)</span>
                            <span class="text-white font-bold">{{ $statusCounts['planning'] }}</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-800">
                            <div class="h-full rounded-full bg-indigo-500" style="width: {{ $activeProjectsCount > 0 ? ($statusCounts['planning'] / max($activeProjectsCount, 1)) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-medium mb-1">
                            <span class="text-emerald-400">Selesai (Completed)</span>
                            <span class="text-white font-bold">{{ $statusCounts['completed'] }}</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-800">
                            <div class="h-full rounded-full bg-emerald-500" style="width: 100%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs font-medium mb-1">
                            <span class="text-amber-400">Ditunda (On Hold)</span>
                            <span class="text-white font-bold">{{ $statusCounts['on_hold'] }}</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-800">
                            <div class="h-full rounded-full bg-amber-500" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enterprise Compliance Badge -->
            <div class="p-5 rounded-2xl bg-gradient-to-r from-slate-900 to-slate-950 border border-slate-800 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Enterprise Security</h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">Audit trail mutasi biaya dan otorisasi bertingkat aktif.</p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

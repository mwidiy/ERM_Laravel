<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Enterprise Resource Planning System for Professional Services and Project-Based Corporations.">
    <title>@yield('title', 'Dashboard') | NEXUS Enterprise ERP</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #090d16;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }
    </style>
</head>
<body class="h-full flex overflow-hidden bg-slate-950 text-slate-100">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-40 bg-black/70 backdrop-blur-xs hidden lg:hidden transition-opacity"></div>

    <!-- Main Sidebar -->
    <aside id="main-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-950/95 border-r border-slate-800/80 backdrop-blur-md flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static">
        
        <!-- Brand Logo Header -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800/80 bg-slate-950/40">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-sky-400 flex items-center justify-center shadow-lg shadow-indigo-500/25 ring-1 ring-white/20 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-lg tracking-tight text-white group-hover:text-indigo-300 transition-colors">NEXUS<span class="text-indigo-400 font-extrabold">.ERP</span></span>
                    </div>
                    <span class="text-[10px] tracking-widest uppercase font-semibold text-slate-400 block -mt-0.5">Services & Project Edition</span>
                </div>
            </a>
            
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Corporate Project Stats Indicator -->
        <div class="px-5 py-4 m-3 rounded-xl bg-gradient-to-br from-indigo-950/40 to-slate-900/60 border border-indigo-500/20 shadow-inner">
            <div class="flex items-center justify-between text-xs text-slate-400 mb-1">
                <span class="font-medium">Periode Fiskal</span>
                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">Q4 2026</span>
            </div>
            <div class="text-sm font-bold text-white flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Active Cycle Delivery</span>
            </div>
        </div>

        <!-- Navigation Menu Links -->
        <nav class="flex-1 px-3 py-2 space-y-1 overflow-y-auto" id="sidebar-nav">
            
            <div class="px-3 pt-2 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-300">Modul Utama</div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('dashboard') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-sm' : 'text-slate-200 hover:text-white hover:bg-slate-900/80' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-indigo-400' : 'text-slate-300 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard & KPI</span>
            </a>

            <!-- Projects -->
            <a href="{{ route('projects.index') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('projects.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-sm' : 'text-slate-200 hover:text-white hover:bg-slate-900/80' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('projects.*') ? 'text-indigo-400' : 'text-slate-300 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                    </svg>
                    <span>Proyek (Projects)</span>
                </div>
                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-slate-800 text-slate-300 border border-slate-700/60">WBS</span>
            </a>

            @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
            <!-- Clients -->
            <a href="{{ route('clients.index') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('clients.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-sm' : 'text-slate-200 hover:text-white hover:bg-slate-900/80' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('clients.*') ? 'text-indigo-400' : 'text-slate-300 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Master Klien (CRM)</span>
                </div>
            </a>
            @endif

            @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'finance']))
            <!-- Services Catalog -->
            <a href="{{ route('services.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('services.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-sm' : 'text-slate-200 hover:text-white hover:bg-slate-900/80' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('services.*') ? 'text-indigo-400' : 'text-slate-300 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                <span>Katalog Layanan & Tarif</span>
            </a>
            @endif

            @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
            <!-- Team / Internal Resources -->
            <a href="{{ route('team.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('team.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-sm' : 'text-slate-200 hover:text-white hover:bg-slate-900/80' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('team.*') ? 'text-indigo-400' : 'text-slate-300 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Tim & Rate Per Jam</span>
            </a>
            @endif

            <div class="px-3 pt-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-300">Eksekusi & Keuangan</div>

            <!-- Tasks & Kanban (WBS) -->
            <a href="{{ route('tasks.index') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('tasks.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-sm' : 'text-slate-200 hover:text-white hover:bg-slate-900/80' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('tasks.*') ? 'text-indigo-400' : 'text-slate-300 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span>Tugas & Kanban (WBS)</span>
                </div>
            </a>

            <!-- Timesheet & Resource Tracking -->
            <a href="{{ route('timesheets.index') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('timesheets.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-sm' : 'text-slate-200 hover:text-white hover:bg-slate-900/80' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('timesheets.*') ? 'text-indigo-400' : 'text-slate-300 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Timesheet & Jam Kerja</span>
                </div>
                <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Aktif</span>
            </a>

            <!-- Project Expenses (Fase 3) -->
            <a href="{{ route('expenses.index') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('expenses.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-sm' : 'text-slate-200 hover:text-white hover:bg-slate-900/80' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('expenses.*') ? 'text-indigo-400' : 'text-slate-300 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Biaya & Beban Langsung</span>
                </div>
            </a>

            @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'finance', 'project_manager']))
            <!-- Invoicing & Billing (Fase 3) -->
            <a href="{{ route('invoices.index') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('invoices.*') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-sm' : 'text-slate-200 hover:text-white hover:bg-slate-900/80' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('invoices.*') ? 'text-indigo-400' : 'text-slate-300 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Faktur & Piutang (AR)</span>
                </div>
                <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-indigo-500/10 text-indigo-400 border border-indigo-500/30">Termin</span>
            </a>
            @endif

            @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'finance']))
            <!-- Project Profitability (Fase 4) -->
            <a href="{{ route('profitability.index') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('profitability.index') || request()->routeIs('profitability.show') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-sm' : 'text-slate-200 hover:text-white hover:bg-slate-900/80' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('profitability.index') ? 'text-indigo-400' : 'text-slate-300 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    <span>Laba Rugi Proyek</span>
                </div>
                <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Margin</span>
            </a>

            <!-- AR Aging Schedule (Fase 4) -->
            <a href="{{ route('profitability.arAging') }}" 
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all group {{ request()->routeIs('profitability.arAging') ? 'bg-indigo-600/20 text-indigo-300 border border-indigo-500/30 shadow-sm' : 'text-slate-200 hover:text-white hover:bg-slate-900/80' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 {{ request()->routeIs('profitability.arAging') ? 'text-indigo-400' : 'text-slate-300 group-hover:text-slate-100' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Jadwal Umur Piutang (Aging)</span>
                </div>
            </a>
            @endif
        </nav>

        <!-- Current User Profile & Logout Action -->
        @auth
        <div class="p-3 border-t border-slate-800/80 bg-slate-950/60">
            <div class="flex items-center justify-between gap-2 p-2 rounded-xl bg-slate-900/60 border border-slate-800/70">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="relative shrink-0">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-indigo-500 via-indigo-600 to-sky-400 flex items-center justify-center font-bold text-white text-xs ring-2 ring-indigo-500/30">
                            {{ Auth::user()->initials }}
                        </div>
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-slate-950"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-semibold text-white truncate" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-slate-400 truncate">{{ Auth::user()->position ?? 'Corporate Staff' }}</div>
                    </div>
                </div>

                <!-- Logout Button -->
                <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sesi kerja NEXUS.ERP?')">
                    @csrf
                    <button type="submit" 
                            class="p-2 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 border border-transparent hover:border-rose-500/30 transition-all"
                            title="Keluar dari Sistem (Logout)">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
            
            <div class="mt-2 px-1 flex items-center justify-between">
                <span class="text-[10px] text-slate-400 font-medium">Hak Otoritas:</span>
                {!! Auth::user()->role_badge !!}
            </div>
        </div>
        @endauth
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-950">
        
        <!-- Top Navbar -->
        <header class="h-20 bg-slate-950/80 border-b border-slate-800/80 backdrop-blur-md flex items-center justify-between px-4 sm:px-6 lg:px-8 z-30">
            
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Page Breadcrumbs -->
                <div class="flex items-center gap-2 text-sm text-slate-400">
                    <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">ERP</a>
                    <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-white font-medium">@yield('page_title', 'Dashboard')</span>
                </div>
            </div>

            <!-- Top Right Action Controls -->
            <div class="flex items-center gap-3">
                
                @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
                <!-- Quick Action New Project -->
                <a href="{{ route('projects.create') }}" class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold shadow-md shadow-indigo-600/30 transition-all active:scale-95">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Proyek Baru</span>
                </a>

                <!-- Quick Action New Client -->
                <a href="{{ route('clients.create') }}" class="hidden md:inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 text-xs font-semibold border border-slate-700/80 transition-all">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span>Tambah Klien</span>
                </a>
                @endif

                @auth
                <!-- User Pill on Navbar -->
                <div class="hidden sm:flex items-center gap-2.5 pl-3 border-l border-slate-800">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center font-bold text-white text-xs ring-1 ring-white/20">
                        {{ Auth::user()->initials }}
                    </div>
                    <div class="text-left hidden md:block">
                        <span class="block text-xs font-semibold text-white leading-tight truncate max-w-[120px]">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-slate-400 leading-none capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
                    </div>
                </div>
                @endauth
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            
            <!-- Flash Message Alerts -->
            @if(session('success'))
                <div id="flash-message" class="flex items-center justify-between p-4 rounded-xl bg-emerald-950/50 border border-emerald-500/40 text-emerald-200 text-sm shadow-lg shadow-emerald-950/40">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button onclick="document.getElementById('flash-message').remove()" class="text-emerald-400 hover:text-emerald-200 p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            @endif

            @if(session('error') || (isset($errors) && $errors->any()))
                <div id="flash-error" class="flex items-start justify-between p-4 rounded-xl bg-rose-950/50 border border-rose-500/40 text-rose-200 text-sm shadow-lg shadow-rose-950/40">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-rose-500/20 flex items-center justify-center text-rose-400 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <span class="font-semibold block mb-1">Harap periksa kesalahan berikut:</span>
                            @if(session('error'))
                                <p>{{ session('error') }}</p>
                            @endif
                            @if(isset($errors) && $errors->any())
                                <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-300">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    <button onclick="document.getElementById('flash-error').remove()" class="text-rose-400 hover:text-rose-200 p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            @endif

            @yield('content')

        </main>
    </div>

    <!-- Vanilla Javascript Helpers -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>

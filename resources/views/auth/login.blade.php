<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Enterprise Resource Planning System for Professional Services and Project-Based Corporations.">
    <title>Login Otoritas Sistem | NEXUS Enterprise ERP</title>

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
        .bg-grid-pattern {
            background-size: 36px 36px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
</head>
<body class="min-h-full flex items-center justify-center p-4 sm:p-6 lg:p-8 bg-slate-950 text-slate-100 bg-grid-pattern relative overflow-x-hidden">

    <!-- Background Ambient Glows -->
    <div class="fixed top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[140px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-10 right-10 w-[400px] h-[400px] bg-sky-500/10 rounded-full blur-[120px] pointer-events-none -z-10"></div>

    <div class="w-full max-w-5xl my-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
        
        <!-- Left Column: Corporate Brand Showcase & Role Matrix -->
        <div class="lg:col-span-6 flex flex-col justify-between p-6 sm:p-8 rounded-3xl bg-slate-900/60 border border-slate-800/80 backdrop-blur-xl shadow-2xl relative overflow-hidden">
            <div class="absolute -top-24 -right-24 w-60 h-60 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div>
                <!-- Brand Header -->
                <div class="flex items-center gap-3.5 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 via-indigo-500 to-sky-400 flex items-center justify-center shadow-lg shadow-indigo-500/25 ring-1 ring-white/20">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-2xl tracking-tight text-white">NEXUS<span class="text-indigo-400">.ERP</span></span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">Enterprise</span>
                        </div>
                        <span class="text-xs text-slate-400 font-medium">PT Enterprise Solusi Indonesia Tbk</span>
                    </div>
                </div>

                <!-- Headline -->
                <div class="mb-8">
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white mb-2 leading-snug">
                        Sistem Informasi Manajemen Terpadu & Tata Kelola Otoritas
                    </h1>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        Akses modul ERP dibatasi sesuai dengan peran, wewenang jabatan, dan matriks tanggung jawab operasional masing-masing unit kerja.
                    </p>
                </div>

                <!-- Role Matrix Preview Tabs / Cards -->
                <div class="space-y-2.5 mb-8">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Pilih Akun Demo Sesuai Jobdesk:</span>
                    </div>

                    <!-- Role 1: Super Admin -->
                    <button type="button" onclick="selectRole('admin@enterprise.co.id', 'password', 'super_admin')" 
                            class="role-btn w-full text-left p-3 rounded-xl bg-slate-950/70 border border-slate-800 hover:border-rose-500/50 hover:bg-slate-900/90 transition-all flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-rose-500/10 border border-rose-500/30 flex items-center justify-center text-rose-400 font-bold text-xs shrink-0">
                                SA
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-white group-hover:text-rose-300 transition-colors">Alexander Pratama</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase bg-rose-500/20 text-rose-300 border border-rose-500/40">Super Admin</span>
                                </div>
                                <div class="text-[11px] text-slate-400 truncate">Managing Director • Akses Penuh / Bypass Otoritas</div>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-rose-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Role 2: Ops Admin -->
                    <button type="button" onclick="selectRole('diana.admin@enterprise.co.id', 'password', 'admin')" 
                            class="role-btn w-full text-left p-3 rounded-xl bg-slate-950/70 border border-slate-800 hover:border-indigo-500/50 hover:bg-slate-900/90 transition-all flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400 font-bold text-xs shrink-0">
                                AD
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-white group-hover:text-indigo-300 transition-colors">Diana Wijaya, S.Kom</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase bg-indigo-500/20 text-indigo-300 border border-indigo-500/40">Admin</span>
                                </div>
                                <div class="text-[11px] text-slate-400 truncate">Operations Admin • Master CRM, Layanan, Tim & P&L</div>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Role 3: Project Manager -->
                    <button type="button" onclick="selectRole('budi.pm@enterprise.co.id', 'password', 'project_manager')" 
                            class="role-btn w-full text-left p-3 rounded-xl bg-slate-950/70 border border-slate-800 hover:border-sky-500/50 hover:bg-slate-900/90 transition-all flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-sky-500/10 border border-sky-500/30 flex items-center justify-center text-sky-400 font-bold text-xs shrink-0">
                                PM
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-white group-hover:text-sky-300 transition-colors">Budi Santoso, PMP</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase bg-sky-500/20 text-sky-300 border border-sky-500/40">Project Manager</span>
                                </div>
                                <div class="text-[11px] text-slate-400 truncate">Delivery Manager • WBS Tasks, Persetujuan Jam & Biaya</div>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-sky-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Role 4: Finance -->
                    <button type="button" onclick="selectRole('anisa.finance@enterprise.co.id', 'password', 'finance')" 
                            class="role-btn w-full text-left p-3 rounded-xl bg-slate-950/70 border border-slate-800 hover:border-emerald-500/50 hover:bg-slate-900/90 transition-all flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 font-bold text-xs shrink-0">
                                FN
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-white group-hover:text-emerald-300 transition-colors">Anisa Tri Putri, S.E., Ak.</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">Finance</span>
                                </div>
                                <div class="text-[11px] text-slate-400 truncate">Finance & Tax • Penagihan Faktur, Umur Piutang, P&L</div>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <!-- Role 5: Consultant -->
                    <button type="button" onclick="selectRole('siti.tech@enterprise.co.id', 'password', 'consultant')" 
                            class="role-btn w-full text-left p-3 rounded-xl bg-slate-950/70 border border-slate-800 hover:border-violet-500/50 hover:bg-slate-900/90 transition-all flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-violet-500/10 border border-violet-500/30 flex items-center justify-center text-violet-400 font-bold text-xs shrink-0">
                                CS
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-white group-hover:text-violet-300 transition-colors">Siti Nurhaliza, M.Kom</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase bg-violet-500/20 text-violet-300 border border-violet-500/40">Consultant</span>
                                </div>
                                <div class="text-[11px] text-slate-400 truncate">Lead Cloud Architect • Log Timesheet, Klaim Biaya, Eksekusi WBS</div>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-violet-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-500">
                <span>Versi Arsitektur ERP v4.2</span>
                <span class="flex items-center gap-1.5 text-emerald-400 font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Sistem Operasional Stabil
                </span>
            </div>
        </div>

        <!-- Right Column: Authentication Form -->
        <div class="lg:col-span-6 flex flex-col justify-center p-6 sm:p-10 rounded-3xl bg-slate-900/90 border border-slate-800 backdrop-blur-2xl shadow-2xl">
            
            <div class="mb-8">
                <span class="text-xs font-bold uppercase tracking-widest text-indigo-400 block mb-1">Keamanan Korporasi</span>
                <h2 class="text-2xl font-bold tracking-tight text-white">Masuk ke Portal Kerja</h2>
                <p class="text-sm text-slate-400 mt-1">Gunakan alamat email resmi perusahaan dan password terdaftar.</p>
            </div>

            <!-- Session Alert Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-950/60 border border-emerald-500/40 text-emerald-200 text-xs flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-950/60 border border-rose-500/40 text-rose-200 text-xs">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-rose-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <div class="font-semibold mb-1">Gagal Mengakses Sistem:</div>
                            @if(session('error'))
                                <p>{{ session('error') }}</p>
                            @endif
                            @foreach($errors->all() as $err)
                                <p>{{ $err }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-5" id="loginForm">
                @csrf

                <!-- Selected Role Indicator Pill -->
                <div id="selected-role-indicator" class="hidden p-2.5 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-xs text-indigo-300 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                        <span class="font-semibold" id="selected-role-name">Jobdesk Terpilih: Super Admin</span>
                    </div>
                    <span class="text-[10px] text-slate-400">Siap login</span>
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                        Alamat Email Resmi <span class="text-rose-400">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', 'admin@enterprise.co.id') }}" 
                               required 
                               placeholder="nama@enterprise.co.id"
                               class="w-full pl-11 pr-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                            Kata Sandi (Password) <span class="text-rose-400">*</span>
                        </label>
                        <span class="text-xs text-slate-500">Default: <code class="text-indigo-400 font-mono">password</code></span>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               value="password"
                               required 
                               placeholder="••••••••"
                               class="w-full pl-11 pr-11 py-3 bg-slate-950/80 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-mono">
                        
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-200">
                            <svg id="eye-icon" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password Note -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-slate-300 cursor-pointer">
                        <input type="checkbox" name="remember" value="1" checked class="w-4 h-4 rounded bg-slate-950 border-slate-800 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-950">
                        <span>Ingat sesi kerja saya di browser ini</span>
                    </label>
                    <span class="text-slate-500">SSL 256-bit Encrypted</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        id="submit-btn"
                        class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-400 text-white font-semibold text-sm shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 transition-all flex items-center justify-center gap-2 group active:scale-[0.99]">
                    <span>Masuk ke Dashboard ERP</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-800/80 text-center">
                <p class="text-xs text-slate-500">
                    Sistem terlindungi otorisasi multi-level. Akses tidak sah akan dicatat pada Audit Trail dan dilaporkan kepada Tim Kepatuhan Korporasi.
                </p>
            </div>
        </div>

    </div>

    <!-- Script for Role Quick Selection and Password Toggle -->
    <script>
        function selectRole(email, password, roleCode) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;

            const roleLabels = {
                'super_admin': 'Super Administrator (Alexander Pratama)',
                'admin': 'Operations Administrator (Diana Wijaya)',
                'project_manager': 'Project Delivery Manager (Budi Santoso)',
                'finance': 'Finance & Tax Manager (Anisa Tri Putri)',
                'consultant': 'Lead Enterprise Consultant (Siti Nurhaliza)'
            };

            const indicator = document.getElementById('selected-role-indicator');
            const roleNameSpan = document.getElementById('selected-role-name');
            indicator.classList.remove('hidden');
            roleNameSpan.textContent = 'Kredensial Dimuat: ' + (roleLabels[roleCode] || roleCode);

            // Subtle highlight flash on the submit button
            const btn = document.getElementById('submit-btn');
            btn.classList.add('ring-2', 'ring-indigo-400');
            setTimeout(() => {
                btn.classList.remove('ring-2', 'ring-indigo-400');
            }, 600);
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />`;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        }
    </script>
</body>
</html>

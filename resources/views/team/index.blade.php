@extends('layouts.app')

@section('title', 'Alokasi & Tarif Personil Tim')
@section('page_title', 'Data Tim Konsultan & Internal Cost Rate')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-800">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Personil Tim & Tarif Biaya Tenaga Kerja</h1>
            <p class="text-sm text-slate-400 mt-1">Kelola data konsultan, Project Manager, dan tarif per jam untuk kalkulasi beban riil proyek.</p>
        </div>
        <button onclick="openTeamModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
            <span>+ Registrasi Personil Baru</span>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Personil</span>
            <div class="text-2xl font-black text-white mt-1">{{ $stats['total'] }} <span class="text-xs font-normal text-slate-400">Orang</span></div>
        </div>
        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Project Delivery Manager</span>
            <div class="text-2xl font-black text-sky-400 mt-1">{{ $stats['pm'] }} <span class="text-xs font-normal text-slate-400">PM Aktif</span></div>
        </div>
        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Konsultan & Engineer</span>
            <div class="text-2xl font-black text-indigo-400 mt-1">{{ $stats['consultants'] }} <span class="text-xs font-normal text-slate-400">Spesialis</span></div>
        </div>
        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Finance & Pajak</span>
            <div class="text-2xl font-black text-emerald-400 mt-1">{{ $stats['finance'] }} <span class="text-xs font-normal text-slate-400">Officer</span></div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Nama Personil</th>
                        <th class="px-6 py-4">Jabatan & Spesialisasi</th>
                        <th class="px-6 py-4">Hak Akses (Role)</th>
                        <th class="px-6 py-4">Tarif Biaya (Internal Cost Rate)</th>
                        <th class="px-6 py-4">Penugasan Proyek</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($team as $user)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center font-bold text-white text-xs ring-2 ring-indigo-500/30 shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $user->email }} • {{ $user->phone ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-200 font-medium">{{ $user->position ?? 'Konsultan Teknis' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $roleBadge = match($user->role) {
                                        'admin' => 'bg-rose-500/10 text-rose-400 border-rose-500/30',
                                        'project_manager' => 'bg-sky-500/10 text-sky-400 border-sky-500/30',
                                        'finance' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                        default => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/30'
                                    };
                                    $roleLabel = match($user->role) {
                                        'admin' => 'Administrator',
                                        'project_manager' => 'Project Manager',
                                        'finance' => 'Finance & Tax',
                                        default => 'Consultant / Staff'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $roleBadge }}">
                                    {{ $roleLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-emerald-400">
                                Rp {{ number_format($user->hourly_rate, 0, ',', '.') }}
                                <span class="text-slate-400 font-normal text-[10px] block">/ Jam Kerja</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700">
                                    {{ $user->projects_count + $user->managed_projects_count }} Proyek
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                    Aktif
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                Belum ada data personil tim.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Registrasi Personil -->
<div id="team-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white">Registrasi Personil Tim Baru</h3>
            <button onclick="closeTeamModal()" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form action="{{ route('team.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Nama Lengkap Personil <span class="text-rose-400">*</span></label>
                <input type="text" name="name" placeholder="Contoh: Farhan Syahputra, S.T." required
                       class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Email Kantor <span class="text-rose-400">*</span></label>
                    <input type="email" name="email" placeholder="farhan@enterprise.co.id" required
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Nomor Telepon / WA</label>
                    <input type="text" name="phone" placeholder="08123456789"
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Hak Akses (Role) <span class="text-rose-400">*</span></label>
                    <select name="role" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="consultant">Consultant / Engineer</option>
                        <option value="project_manager">Project Manager</option>
                        <option value="finance">Finance & Accounting</option>
                        <option value="admin">Super Admin / Direksi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Tarif Biaya Per Jam (IDR) <span class="text-rose-400">*</span></label>
                    <input type="number" name="hourly_rate" value="200000" min="0" step="10000" required
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white font-semibold text-emerald-400 focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Jabatan Resmi <span class="text-rose-400">*</span></label>
                <input type="text" name="position" placeholder="Contoh: Senior DevOps Engineer & Security Specialist" required
                       class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                <button type="button" onclick="closeTeamModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30">Daftarkan Personil</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openTeamModal() {
        const modal = document.getElementById('team-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeTeamModal() {
        const modal = document.getElementById('team-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
@endsection

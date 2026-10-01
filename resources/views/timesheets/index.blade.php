@extends('layouts.app')

@section('title', 'Timesheet & Resource Tracking')
@section('page_title', 'Timesheet & Manajemen Jam Kerja Konsultan')

@section('content')
<div class="space-y-6">

    <!-- Top Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-800">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Timesheet & Jam Kerja Konsultan</h1>
            <p class="text-sm text-slate-400 mt-1">Pencatatan log aktivitas harian tim, jam billable klien, dan persetujuan biaya tenaga kerja riil.</p>
        </div>
        <button onclick="openTimesheetModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            <span>+ Catat Jam Kerja Baru</span>
        </button>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Jam Logged</span>
            <div class="text-2xl font-black text-white mt-1">{{ number_format($stats['total_hours'], 1) }} <span class="text-xs font-normal text-slate-400">Jam Kerja</span></div>
            <span class="text-[11px] text-indigo-400 mt-1 block">Akumulasi seluruh personil</span>
        </div>

        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jam Disetujui (Approved)</span>
            <div class="text-2xl font-black text-emerald-400 mt-1">{{ number_format($stats['approved_hours'], 1) }} <span class="text-xs font-normal text-slate-400">Jam</span></div>
            <span class="text-[11px] text-emerald-400 mt-1 block">Telah diverifikasi PM</span>
        </div>

        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Realisasi Biaya Tenaga Kerja</span>
            <div class="text-xl font-black text-sky-400 mt-1">Rp {{ number_format($stats['total_cost'], 0, ',', '.') }}</div>
            <span class="text-[11px] text-sky-400 mt-1 block">Beban biaya riil proyek</span>
        </div>

        <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Antrian Review PM</span>
            <div class="text-2xl font-black text-amber-400 mt-1">{{ $stats['pending_count'] }} <span class="text-xs font-normal text-slate-400">Log Pending</span></div>
            <span class="text-[11px] text-amber-400 mt-1 block">Menunggu persetujuan</span>
        </div>

    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-xl bg-slate-900/70 border border-slate-800">
        <form method="GET" action="{{ route('timesheets.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="w-full sm:w-auto flex-1 min-w-[200px]">
                <select name="project_id" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Proyek</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ $projectId == $p->id ? 'selected' : '' }}>
                            {{ $p->project_code }} - {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-auto min-w-[150px]">
                <select name="user_id" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Personil</option>
                    @foreach($team as $m)
                        <option value="{{ $m->id }}" {{ $userId == $m->id ? 'selected' : '' }}>
                            {{ $m->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-auto min-w-[130px]">
                <select name="status" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="submitted" {{ $status === 'submitted' ? 'selected' : '' }}>Submitted (Pending)</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition-all">
                    Filter
                </button>
                @if($projectId || $userId || $status)
                    <a href="{{ route('timesheets.index') }}" class="px-3 py-2 bg-slate-800/50 hover:bg-slate-800 text-slate-400 hover:text-white rounded-lg text-xs transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Timesheets Data Table -->
    <div class="rounded-2xl bg-slate-900/90 border border-slate-800 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-950/60 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="px-6 py-4">Tanggal & Personil</th>
                        <th class="px-6 py-4">Proyek & Klien</th>
                        <th class="px-6 py-4">Uraian Pekerjaan (Log)</th>
                        <th class="px-6 py-4 text-center">Durasi (Jam)</th>
                        <th class="px-6 py-4">Biaya Tenaga Kerja (Cost)</th>
                        <th class="px-6 py-4 text-center">Status Approval</th>
                        <th class="px-6 py-4 text-right">Aksi PM</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($timesheets as $ts)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-white">{{ $ts->entry_date->format('d M Y') }}</div>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="w-5 h-5 rounded-full bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center font-bold text-white text-[9px]">
                                        {{ strtoupper(substr($ts->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="text-xs text-slate-300 font-medium">{{ $ts->user->name ?? 'User' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-mono font-bold px-1.5 py-0.5 rounded bg-slate-800 text-indigo-400 border border-slate-700/60 block w-fit mb-1">
                                    {{ $ts->project->project_code ?? 'PRJ' }}
                                </span>
                                <div class="font-bold text-white text-xs truncate max-w-[200px]">{{ $ts->project->name ?? '-' }}</div>
                                <span class="text-[11px] text-slate-400">{{ $ts->project->client->company_name ?? '' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($ts->task)
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-indigo-500/10 text-indigo-300 border border-indigo-500/20 inline-block mb-1">
                                        Tugas: {{ $ts->task->title }}
                                    </span>
                                @endif
                                <div class="text-xs text-slate-300 line-clamp-2 leading-relaxed">
                                    {{ $ts->description }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-base font-black text-white">{{ number_format($ts->hours, 1) }}</span>
                                <span class="text-[10px] text-slate-400 block">Jam</span>
                                @if($ts->is_billable)
                                    <span class="text-[9px] font-bold uppercase text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 rounded border border-emerald-500/20">Billable</span>
                                @else
                                    <span class="text-[9px] font-medium text-slate-400 bg-slate-800 px-1.5 py-0.5 rounded">Overhead</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-sky-400">Rp {{ number_format($ts->total_cost, 0, ',', '.') }}</div>
                                <span class="text-[10px] text-slate-400 block">@ Rp {{ number_format($ts->hourly_rate, 0, ',', '.') }}/jam</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($ts->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                        Disetujui
                                    </span>
                                    <span class="text-[10px] text-slate-400 block mt-1">oleh {{ $ts->approver->name ?? 'PM' }}</span>
                                @elseif($ts->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/30">
                                        Ditolak
                                    </span>
                                    @if($ts->rejection_reason)
                                        <span class="text-[10px] text-rose-300 block mt-1 max-w-[140px] truncate" title="{{ $ts->rejection_reason }}">"{{ $ts->rejection_reason }}"</span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                        Pending Review
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($ts->status === 'submitted')
                                    @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager']))
                                    <div class="flex items-center justify-end gap-1.5">
                                        <form action="{{ route('timesheets.approve', $ts) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="Setujui Jam Kerja" class="px-2.5 py-1 rounded-lg bg-emerald-600/30 hover:bg-emerald-600 text-emerald-300 hover:text-white border border-emerald-500/40 text-xs font-bold transition-all">
                                                Setujui
                                            </button>
                                        </form>

                                        <button onclick="openRejectModal({{ $ts->id }})" title="Tolak Timesheet" class="px-2.5 py-1 rounded-lg bg-rose-950/40 hover:bg-rose-900 text-rose-400 hover:text-white border border-rose-500/30 text-xs font-semibold transition-all">
                                            Tolak
                                        </button>
                                    </div>
                                    @else
                                    <span class="text-xs text-amber-400/80 italic font-medium">Menunggu Review PM</span>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-500">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                Belum ada data log jam kerja tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($timesheets->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $timesheets->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal 1: Catat Jam Kerja Baru -->
<div id="timesheet-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white">Catat Log Jam Kerja (Timesheet)</h3>
            <button onclick="closeTimesheetModal()" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form action="{{ route('timesheets.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Personil Yang Mengerjakan <span class="text-rose-400">*</span></label>
                <select name="user_id" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                    @foreach($team as $m)
                        <option value="{{ $m->id }}">
                            {{ $m->name }} ({{ $m->position ?? 'Konsultan' }}) - Rate: Rp {{ number_format($m->hourly_rate, 0, ',', '.') }}/jam
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Proyek Yang Dikerjakan <span class="text-rose-400">*</span></label>
                <select name="project_id" id="ts_project_select" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                    <option value="">-- Pilih Proyek --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ $projectId == $p->id ? 'selected' : '' }}>
                            {{ $p->project_code }} - {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Tanggal Kerja <span class="text-rose-400">*</span></label>
                    <input type="date" name="entry_date" value="{{ now()->format('Y-m-d') }}" required
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Durasi Kerja (Jam) <span class="text-rose-400">*</span></label>
                    <input type="number" name="hours" value="8" min="0.25" max="24" step="0.25" required
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white font-bold text-indigo-400 focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-950/60 border border-slate-800">
                <input type="checkbox" name="is_billable" value="1" id="is_billable" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                <label for="is_billable" class="text-xs text-slate-300 font-medium">
                    <strong class="text-white block">Dapat Ditagihkan ke Klien (Billable Hours)</strong>
                    Centang jika aktivitas ini termasuk dalam lingkup kontrak kerja klien.
                </label>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Deskripsi Hasil Kerja / Log Aktivitas <span class="text-rose-400">*</span></label>
                <textarea name="description" rows="3" placeholder="Uraikan fitur yang dicoding, dokumen yang diselesaikan, atau meeting teknis yang dihadiri..." required
                          class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                <button type="button" onclick="closeTimesheetModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30">Submit Timesheet</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Tolak Timesheet -->
<div id="reject-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl p-6 space-y-4">
        <h3 class="text-base font-bold text-white">Tolak Pengajuan Timesheet</h3>
        <p class="text-xs text-slate-400">Masukkan alasan penolakan agar personil dapat merevisi log jam kerjanya.</p>

        <form id="reject-form" method="POST" action="">
            @csrf
            @method('PATCH')
            <div class="space-y-4">
                <textarea name="rejection_reason" rows="3" placeholder="Contoh: Jam kerja melebihi alokasi harian atau deskripsi kurang jelas..." required
                          class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-rose-500"></textarea>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-md shadow-rose-600/30">Konfirmasi Tolak</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openTimesheetModal() {
        const modal = document.getElementById('timesheet-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeTimesheetModal() {
        const modal = document.getElementById('timesheet-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function openRejectModal(timesheetId) {
        const form = document.getElementById('reject-form');
        form.action = `/timesheets/${timesheetId}/reject`;
        const modal = document.getElementById('reject-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeRejectModal() {
        const modal = document.getElementById('reject-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
@endsection

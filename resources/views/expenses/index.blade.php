@extends('layouts.app')

@section('page_title', 'Biaya & Beban Proyek')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                <span class="w-2.5 h-6 bg-indigo-500 rounded-full inline-block"></span>
                Beban & Pengeluaran Langsung Proyek
            </h1>
            <p class="text-sm text-slate-400 mt-1">Catat, kelola bukti nota, dan approval pengeluaran operasional proyek non-tenaga kerja.</p>
        </div>
        <button onclick="document.getElementById('modal-add-expense').classList.remove('hidden')" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold shadow-lg shadow-indigo-600/30 transition-all active:scale-95">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Ajukan Beban Baru</span>
        </button>
    </div>

    <!-- Financial KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Approved Expenses -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Beban Disetujui</div>
            <div class="mt-2 text-2xl font-extrabold text-white">Rp {{ number_format($totalApproved, 0, ',', '.') }}</div>
            <div class="mt-2 flex items-center text-xs text-emerald-400 font-medium">
                <i class="fa-solid fa-check-circle mr-1.5"></i> Masuk COGS / Aktual Biaya
            </div>
        </div>

        <!-- Pending Approval -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Menunggu Approval</div>
            <div class="mt-2 text-2xl font-extrabold text-amber-400">Rp {{ number_format($totalPending, 0, ',', '.') }}</div>
            <div class="mt-2 flex items-center text-xs text-amber-300 font-medium">
                <i class="fa-solid fa-clock mr-1.5"></i> {{ $pendingCount }} pengajuan pending
            </div>
        </div>

        <!-- Billable to Client -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Billable ke Klien</div>
            <div class="mt-2 text-2xl font-extrabold text-cyan-400">Rp {{ number_format($totalBillable, 0, ',', '.') }}</div>
            <div class="mt-2 flex items-center text-xs text-cyan-300 font-medium">
                <i class="fa-solid fa-file-invoice mr-1.5"></i> Dapat ditagih kembali (reimburse)
            </div>
        </div>

        <!-- Expense Categories -->
        <div class="p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Klasifikasi Beban</div>
            <div class="mt-2 text-2xl font-extrabold text-indigo-400">6 Kategori</div>
            <div class="mt-2 flex items-center text-xs text-slate-400">
                <i class="fa-solid fa-tags mr-1.5"></i> Transport, Lisensi, Subcon, dll.
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm">
        <form method="GET" action="{{ route('expenses.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                    <option value="">-- Semua Status --</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Menunggu Review (Submitted)</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                    <option value="reimbursed" {{ request('status') === 'reimbursed' ? 'selected' : '' }}>Direimburse (Reimbursed)</option>
                </select>
            </div>
            <div>
                <select name="category" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                    <option value="">-- Semua Kategori --</option>
                    <option value="transport_travel" {{ request('category') === 'transport_travel' ? 'selected' : '' }}>Transport & Dinas</option>
                    <option value="software_licenses" {{ request('category') === 'software_licenses' ? 'selected' : '' }}>Lisensi Software/Cloud</option>
                    <option value="equipment_materials" {{ request('category') === 'equipment_materials' ? 'selected' : '' }}>Peralatan & Material</option>
                    <option value="meals_hospitality" {{ request('category') === 'meals_hospitality' ? 'selected' : '' }}>Konsumsi & Jamuan</option>
                    <option value="subcontractor" {{ request('category') === 'subcontractor' ? 'selected' : '' }}>Subkontraktor/Outsource</option>
                    <option value="operational_other" {{ request('category') === 'operational_other' ? 'selected' : '' }}>Operasional Lainnya</option>
                </select>
            </div>
            <div>
                <select name="project_id" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                    <option value="">-- Semua Proyek --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->project_code }} - {{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-semibold transition-all">
                    Filter Data
                </button>
                @if(request()->anyFilled(['status', 'category', 'project_id']))
                    <a href="{{ route('expenses.index') }}" class="p-2.5 rounded-xl bg-slate-800/60 hover:bg-slate-800 text-slate-400 hover:text-white" title="Reset Filter">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Expenses Table -->
    <div class="rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs uppercase bg-slate-950/70 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-4 font-semibold">Kode & Tanggal</th>
                        <th class="px-5 py-4 font-semibold">Proyek & Klien</th>
                        <th class="px-5 py-4 font-semibold">Kategori & Keterangan</th>
                        <th class="px-5 py-4 font-semibold">Diajukan Oleh</th>
                        <th class="px-5 py-4 font-semibold text-right">Nominal Beban</th>
                        <th class="px-5 py-4 font-semibold text-center">Billable</th>
                        <th class="px-5 py-4 font-semibold text-center">Status</th>
                        <th class="px-5 py-4 font-semibold text-right">Aksi Approval</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/70">
                    @forelse($expenses as $item)
                        <tr class="hover:bg-slate-850/40 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-mono text-xs font-bold text-indigo-400">{{ $item->expense_code }}</span>
                                <div class="text-xs text-slate-500 mt-0.5">{{ $item->expense_date->format('d M Y') }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('projects.show', $item->project_id) }}" class="font-semibold text-white hover:text-indigo-400 transition-colors">
                                    {{ $item->project->name }}
                                </a>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $item->project->client->company_name ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-4 max-w-xs">
                                <div class="font-medium text-slate-200">{{ $item->title }}</div>
                                <div class="text-xs text-indigo-400/90 font-medium">{{ $item->category_label }}</div>
                                @if($item->description)
                                    <div class="text-xs text-slate-500 truncate mt-0.5">{{ $item->description }}</div>
                                @endif
                                @if($item->receipt_path)
                                    <a href="{{ asset('storage/' . $item->receipt_path) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-cyan-400 hover:underline mt-1">
                                        <i class="fa-solid fa-paperclip"></i> Lihat Nota
                                    </a>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="font-medium text-slate-200">{{ $item->user->name }}</div>
                                <div class="text-xs text-slate-500">{{ $item->user->position ?? 'Team Member' }}</div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right font-bold text-white">
                                Rp {{ number_format($item->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                @if($item->is_billable_to_client)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                        Billable
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-800 text-slate-400">
                                        Non-Billable
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                {!! $item->status_badge !!}
                                @if($item->status === 'approved' && $item->approver)
                                    <div class="text-[10px] text-slate-500 mt-1">Oleh: {{ $item->approver->name }}</div>
                                @elseif($item->status === 'rejected' && $item->rejection_reason)
                                    <div class="text-[10px] text-rose-400 mt-1" title="{{ $item->rejection_reason }}">Ket: {{ Str::limit($item->rejection_reason, 20) }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right space-x-1">
                                @if($item->status === 'submitted')
                                    @if(Auth::check() && Auth::user()->hasRole(['super_admin', 'admin', 'project_manager', 'finance']))
                                    <!-- Approve Button -->
                                    <form method="POST" action="{{ route('expenses.approve', $item->id) }}" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="p-1.5 rounded-lg bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 transition-colors" title="Setujui Pengeluaran">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>

                                    <!-- Reject Button Prompt -->
                                    <button onclick="promptReject('{{ $item->id }}', '{{ $item->expense_code }}')" class="p-1.5 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 transition-colors" title="Tolak">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                    @else
                                    <span class="text-xs text-amber-400/80 italic font-medium">Menunggu Otorisasi</span>
                                    @endif
                                @endif

                                @if($item->status !== 'approved' && $item->status !== 'reimbursed')
                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('expenses.destroy', $item->id) }}" class="inline-block" onsubmit="return confirm('Hapus pengajuan beban ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-rose-400 transition-colors" title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-receipt text-3xl mb-3 text-slate-600 block"></i>
                                Belum ada data pengeluaran proyek yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
            <div class="px-6 py-4 border-t border-slate-800">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Form: Add Expense -->
<div id="modal-add-expense" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm hidden">
    <div class="w-full max-w-xl rounded-2xl bg-slate-900 border border-slate-800 p-6 shadow-2xl space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-receipt text-indigo-400"></i>
                Ajukan Pengeluaran / Beban Proyek
            </h3>
            <button onclick="document.getElementById('modal-add-expense').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Pilih Proyek *</label>
                    <select name="project_id" required class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                        <option value="">-- Pilih Proyek --</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}">{{ $p->project_code }} - {{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Diajukan Oleh *</label>
                    <select name="user_id" required class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->position ?? $u->role }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Kategori Beban *</label>
                    <select name="category" required class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                        <option value="transport_travel">Transport & Dinas Luar</option>
                        <option value="software_licenses">Lisensi Software & Cloud Hosting</option>
                        <option value="equipment_materials">Peralatan, Sewa & Material</option>
                        <option value="meals_hospitality">Konsumsi & Jamuan Klien</option>
                        <option value="subcontractor">Subkontraktor / Spesialis Luar</option>
                        <option value="operational_other">Operasional Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Tanggal Beban *</label>
                    <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Judul / Keperluan Pengeluaran *</label>
                <input type="text" name="title" required placeholder="Contoh: Tiket Pesawat & Akomodasi Kickoff Meeting" class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nominal Pengeluaran (Rp) *</label>
                    <input type="number" name="amount" min="1000" step="1000" required placeholder="Contoh: 1500000" class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Upload Bukti / Nota (PDF/Foto)</label>
                    <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-1.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-400 file:mr-3 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Keterangan Tambahan</label>
                <textarea name="description" rows="2" placeholder="Penjelasan rincian item atau nomor referensi struk..." class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500"></textarea>
            </div>

            <div class="flex items-center gap-2 p-3 rounded-xl bg-slate-950 border border-slate-800/80">
                <input type="checkbox" name="is_billable_to_client" id="is_billable_to_client" value="1" class="w-4 h-4 rounded text-indigo-600 bg-slate-900 border-slate-700 focus:ring-indigo-500">
                <label for="is_billable_to_client" class="text-xs text-slate-300 select-none">
                    <span class="font-semibold text-white">Billable ke Klien:</span> Beban ini akan ditagihkan kembali ke klien pada invoice termin mendatang.
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('modal-add-expense').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-semibold transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold shadow-md shadow-indigo-600/30 transition-all">
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Form: Reject Expense Reason -->
<div id="modal-reject-expense" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm hidden">
    <div class="w-full max-w-md rounded-2xl bg-slate-900 border border-slate-800 p-6 shadow-2xl space-y-4">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-rose-400"></i>
            Tolak Pengajuan Beban
        </h3>
        <p class="text-xs text-slate-400">Berikan alasan penolakan untuk kode beban <span id="reject-expense-code" class="font-mono font-bold text-slate-200"></span>:</p>

        <form id="form-reject-expense" method="POST" action="" class="space-y-4">
            @csrf
            @method('PATCH')
            <textarea name="rejection_reason" required rows="3" placeholder="Contoh: Nota tidak jelas / Melebihi plafon harian per diem..." class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-rose-500"></textarea>

            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-reject-expense').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-sm font-semibold transition-colors">
                    Tolak Beban
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function promptReject(expenseId, expenseCode) {
        document.getElementById('reject-expense-code').textContent = expenseCode;
        document.getElementById('form-reject-expense').action = `/expenses/${expenseId}/reject`;
        document.getElementById('modal-reject-expense').classList.remove('hidden');
    }
</script>
@endpush
@endsection

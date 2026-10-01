@extends('layouts.app')

@section('title', 'Buat Proyek Baru')
@section('page_title', 'Inisiasi Proyek Baru (Project Charter)')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Header -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Inisiasi Kontrak & Proyek Baru</h1>
            <p class="text-sm text-slate-400 mt-1">Definisikan project charter, nilai kontrak, alokasi pagu anggaran (RAB), dan tim delivery.</p>
        </div>
        <a href="{{ route('projects.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs font-semibold border border-slate-700/80 transition-all">
            &larr; Kembali ke Portofolio
        </a>
    </div>

    <!-- Form Container -->
    <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Section 1: Detail Kontrak & Klien -->
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 sm:p-8 space-y-6 shadow-xl">
            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    Informasi Kontrak & Klien
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Identitas proyek, klien pemesan, dan penanggung jawab teknis (Project Manager).</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Kode Proyek (Auto-Generated) <span class="text-rose-400">*</span></label>
                    <input type="text" name="project_code" value="{{ old('project_code', $nextCode) }}" readonly
                           class="w-full px-4 py-2.5 bg-slate-950/80 border border-slate-800 rounded-xl text-sm font-mono text-indigo-400 font-bold focus:outline-none cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Klien Perusahaan <span class="text-rose-400">*</span></label>
                    <select name="client_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="">-- Pilih Klien Korporasi --</option>
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}" {{ (old('client_id', request('client_id')) == $c->id) ? 'selected' : '' }}>
                                {{ $c->company_name }} ({{ $c->client_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama / Judul Proyek <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Core Banking Modernization & Open API BI-FAST" required
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Project Delivery Manager (PM)</label>
                    <select name="project_manager_id" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="">-- Tentukan PM --</option>
                        @foreach($projectManagers as $pm)
                            <option value="{{ $pm->id }}" {{ old('project_manager_id') == $pm->id ? 'selected' : '' }}>
                                {{ $pm->name }} ({{ $pm->position ?? 'Project Manager' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tingkat Prioritas</label>
                    <select name="priority" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium (Standar)</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High (Prioritas Tinggi)</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent / Kritis</option>
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Deskripsi & Ruang Lingkup (Scope of Work)</label>
                    <textarea name="description" rows="3" placeholder="Uraikan tujuan proyek, deliverables utama, dan batas tanggung jawab tim teknis..."
                              class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 2: Finansial, Billing & RAB -->
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 sm:p-8 space-y-6 shadow-xl">
            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Finansial Kontrak & Pagu Anggaran (RAB)
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Penetapan nilai komersial kontrak dan batas anggaran biaya langsung (*direct costs*).</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Model Penagihan (Billing Type) <span class="text-rose-400">*</span></label>
                    <select name="contract_type" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="fixed_fee" {{ old('contract_type') === 'fixed_fee' ? 'selected' : '' }}>Fixed Fee (Termin / Milestone)</option>
                        <option value="time_and_material" {{ old('contract_type') === 'time_and_material' ? 'selected' : '' }}>Time & Material (Jam Kerja)</option>
                        <option value="retainer" {{ old('contract_type') === 'retainer' ? 'selected' : '' }}>Monthly Retainer (Rutin Bulanan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nilai Kontrak Total (IDR) <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" name="contract_value" value="{{ old('contract_value', 500000000) }}" step="1000000" min="0" required
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm font-semibold text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Harga jual resmi ke klien.</span>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Pagu Anggaran RAB (Cost Budget)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" name="budget_cost" value="{{ old('budget_cost', 250000000) }}" step="1000000" min="0"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Maksimal biaya internal untuk margin sehat.</span>
                </div>
            </div>
        </div>

        <!-- Section 3: Timeline & Status Pelaksanaan -->
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 sm:p-8 space-y-6 shadow-xl">
            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                    Jadwal Pelaksanaan & Status Awal
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Rentang waktu eksekusi kontrak kerja dari kick-off hingga serah terima.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tanggal Mulai Proyek <span class="text-rose-400">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Target Selesai (BAST)</label>
                    <input type="date" name="end_date" value="{{ old('end_date', now()->addMonths(4)->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tahap / Status Awal</label>
                    <select name="status" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="planning" {{ old('status') === 'planning' ? 'selected' : '' }}>Planning (Persiapan Dokumen)</option>
                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress (Langsung Berjalan)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Submit & Cancel -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('projects.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 text-sm font-semibold border border-slate-700/80 transition-all">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold shadow-lg shadow-indigo-600/30 transition-all">
                Simpan & Terbitkan Proyek
            </button>
        </div>
    </form>

</div>
@endsection

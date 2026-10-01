@extends('layouts.app')

@section('title', 'Edit Proyek: ' . $project->name)
@section('page_title', 'Perbarui Informasi Proyek')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Perbarui Informasi Proyek: {{ $project->project_code }}</h1>
            <p class="text-sm text-slate-400 mt-1">Ubah status pelaksanaan, progres fisik, dan parameter komersial proyek.</p>
        </div>
        <a href="{{ route('projects.show', $project) }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs font-semibold border border-slate-700/80 transition-all">
            &larr; Kembali ke Workspace
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 sm:p-8 space-y-6 shadow-xl">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1.5">Kode Proyek</label>
                    <input type="text" value="{{ $project->project_code }}" readonly class="w-full px-4 py-2.5 bg-slate-950/80 border border-slate-800 rounded-xl text-sm font-mono text-indigo-400 font-bold cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Klien Perusahaan <span class="text-rose-400">*</span></label>
                    <select name="client_id" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        @foreach($clients as $c)
                            <option value="{{ $c->id }}" {{ old('client_id', $project->client_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->company_name }} ({{ $c->client_code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Proyek <span class="text-rose-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $project->name) }}" required
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Project Manager</label>
                    <select name="project_manager_id" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="">-- Tanpa PM --</option>
                        @foreach($projectManagers as $pm)
                            <option value="{{ $pm->id }}" {{ old('project_manager_id', $project->project_manager_id) == $pm->id ? 'selected' : '' }}>
                                {{ $pm->name }} ({{ $pm->position ?? 'Project Manager' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Prioritas</label>
                    <select name="priority" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="medium" {{ old('priority', $project->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $project->priority) === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority', $project->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="low" {{ old('priority', $project->priority) === 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Model Penagihan</label>
                    <select name="contract_type" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="fixed_fee" {{ old('contract_type', $project->contract_type) === 'fixed_fee' ? 'selected' : '' }}>Fixed Fee</option>
                        <option value="time_and_material" {{ old('contract_type', $project->contract_type) === 'time_and_material' ? 'selected' : '' }}>Time & Material</option>
                        <option value="retainer" {{ old('contract_type', $project->contract_type) === 'retainer' ? 'selected' : '' }}>Monthly Retainer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Status Proyek</label>
                    <select name="status" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="planning" {{ old('status', $project->status) === 'planning' ? 'selected' : '' }}>Planning</option>
                        <option value="in_progress" {{ old('status', $project->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="on_hold" {{ old('status', $project->status) === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $project->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nilai Kontrak Total (Rp) <span class="text-rose-400">*</span></label>
                    <input type="number" name="contract_value" value="{{ old('contract_value', $project->contract_value) }}" step="1000000" required
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Pagu Anggaran RAB (Rp)</label>
                    <input type="number" name="budget_cost" value="{{ old('budget_cost', $project->budget_cost) }}" step="1000000"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Akumulasi Progres Fisik (%)</label>
                    <div class="relative">
                        <input type="number" name="progress_percentage" value="{{ old('progress_percentage', $project->progress_percentage) }}" min="0" max="100" required
                               class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-xs text-slate-400">%</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tanggal Mulai Proyek</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}" required
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Target Selesai (BAST)</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Deskripsi & Ruang Lingkup</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">{{ old('description', $project->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('projects.show', $project) }}" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 text-sm font-semibold border border-slate-700/80 transition-all">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold shadow-lg shadow-indigo-600/30 transition-all">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>
@endsection

@extends('layouts.app')

@section('title', 'Tugas & Kanban WBS')
@section('page_title', 'Work Breakdown Structure (WBS) & Tasks')

@section('content')
<div class="space-y-6">

    <!-- Top Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-800">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Tugas Proyek & Kanban WBS</h1>
            <p class="text-sm text-slate-400 mt-1">Pemantauan eksekusi teknis deliverable, estimasi jam kerja, dan progres per personil.</p>
        </div>
        <button onclick="openTaskModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            <span>+ Buat Tugas Baru</span>
        </button>
    </div>

    <!-- 4 Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Antrian (To Do)</span>
            <div class="text-2xl font-black text-slate-300 mt-1">{{ $stats['todo'] }} <span class="text-xs font-normal text-slate-400">Tugas</span></div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sedang Dikerjakan</span>
            <div class="text-2xl font-black text-sky-400 mt-1">{{ $stats['in_progress'] }} <span class="text-xs font-normal text-slate-400">Tugas</span></div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Review & QA</span>
            <div class="text-2xl font-black text-amber-400 mt-1">{{ $stats['review'] }} <span class="text-xs font-normal text-slate-400">Tugas</span></div>
        </div>
        <div class="p-4 rounded-xl bg-slate-900/90 border border-slate-800 shadow-md">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Selesai (Done)</span>
            <div class="text-2xl font-black text-emerald-400 mt-1">{{ $stats['done'] }} <span class="text-xs font-normal text-slate-400">Tugas</span></div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="p-4 rounded-xl bg-slate-900/70 border border-slate-800">
        <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-wrap items-center gap-3">
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
                <select name="assigned_to" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Personil</option>
                    @foreach($team as $member)
                        <option value="{{ $member->id }}" {{ $assignedTo == $member->id ? 'selected' : '' }}>
                            {{ $member->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-auto min-w-[130px]">
                <select name="priority" class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-xs text-white focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Prioritas</option>
                    <option value="urgent" {{ $priority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="high" {{ $priority === 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ $priority === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ $priority === 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-xs font-bold transition-all">
                    Filter
                </button>
                @if($projectId || $assignedTo || $priority || $status)
                    <a href="{{ route('tasks.index') }}" class="px-3 py-2 bg-slate-800/50 hover:bg-slate-800 text-slate-400 hover:text-white rounded-lg text-xs transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Kanban Board 4 Swimlanes -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
        
        <!-- Column 1: To Do -->
        <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4 space-y-4 flex flex-col">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800/80">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                    <h2 class="font-bold text-sm text-white">To Do (Antrian)</h2>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-slate-800 text-slate-300">{{ $stats['todo'] }}</span>
            </div>

            <div class="space-y-3 flex-1 overflow-y-auto max-h-[700px] pr-1">
                @foreach($tasks->where('status', 'todo') as $task)
                    @include('tasks.partials.task_card', ['task' => $task])
                @endforeach
            </div>
        </div>

        <!-- Column 2: In Progress -->
        <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4 space-y-4 flex flex-col">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800/80">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-400 animate-pulse"></span>
                    <h2 class="font-bold text-sm text-white">In Progress (Berjalan)</h2>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-sky-500/10 text-sky-400 border border-sky-500/30">{{ $stats['in_progress'] }}</span>
            </div>

            <div class="space-y-3 flex-1 overflow-y-auto max-h-[700px] pr-1">
                @foreach($tasks->where('status', 'in_progress') as $task)
                    @include('tasks.partials.task_card', ['task' => $task])
                @endforeach
            </div>
        </div>

        <!-- Column 3: In Review -->
        <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4 space-y-4 flex flex-col">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800/80">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <h2 class="font-bold text-sm text-white">Review & QA</h2>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/30">{{ $stats['review'] }}</span>
            </div>

            <div class="space-y-3 flex-1 overflow-y-auto max-h-[700px] pr-1">
                @foreach($tasks->where('status', 'review') as $task)
                    @include('tasks.partials.task_card', ['task' => $task])
                @endforeach
            </div>
        </div>

        <!-- Column 4: Done -->
        <div class="rounded-2xl bg-slate-900/60 border border-slate-800 p-4 space-y-4 flex flex-col">
            <div class="flex items-center justify-between pb-3 border-b border-slate-800/80">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                    <h2 class="font-bold text-sm text-white">Selesai (Done)</h2>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">{{ $stats['done'] }}</span>
            </div>

            <div class="space-y-3 flex-1 overflow-y-auto max-h-[700px] pr-1">
                @foreach($tasks->where('status', 'done') as $task)
                    @include('tasks.partials.task_card', ['task' => $task])
                @endforeach
            </div>
        </div>

    </div>

</div>

<!-- Modal Buat Tugas Baru -->
<div id="task-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white">Buat Tugas Teknis Baru (WBS Task)</h3>
            <button onclick="closeTaskModal()" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Proyek Terkait <span class="text-rose-400">*</span></label>
                <select name="project_id" required class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                    <option value="">-- Pilih Proyek --</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ $projectId == $p->id ? 'selected' : '' }}>
                            {{ $p->project_code }} - {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Judul Tugas <span class="text-rose-400">*</span></label>
                <input type="text" name="title" placeholder="Contoh: Implementasi modul autentikasi OAuth2" required
                       class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Personil Penanggung Jawab (Assignee)</label>
                <select name="assigned_to" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                    <option value="">-- Belum Ditugaskan --</option>
                    @foreach($team as $m)
                        <option value="{{ $m->id }}">
                            {{ $m->name }} ({{ $m->position ?? 'Konsultan' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Estimasi Jam Kerja</label>
                    <input type="number" name="estimated_hours" value="16" min="0" step="0.5"
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Target Selesai</label>
                    <input type="date" name="due_date" value="{{ now()->addDays(7)->format('Y-m-d') }}"
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Prioritas</label>
                    <select name="priority" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                        <option value="low">Low</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Tahap Awal</label>
                    <select name="status" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="review">Review</option>
                        <option value="done">Done</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Uraian Teknis / Kriteria</label>
                <textarea name="description" rows="2" placeholder="Uraian langkah pengerjaan atau acceptance criteria..."
                          class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                <button type="button" onclick="closeTaskModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30">Simpan Tugas</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openTaskModal() {
        const modal = document.getElementById('task-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeTaskModal() {
        const modal = document.getElementById('task-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
@endsection

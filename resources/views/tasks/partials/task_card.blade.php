<div class="p-4 rounded-xl bg-slate-950/80 border border-slate-800 hover:border-slate-700 transition-all shadow-md space-y-3 group">
    
    <!-- Top Tags -->
    <div class="flex items-center justify-between gap-1.5">
        <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded bg-slate-800 text-indigo-400 border border-slate-700/60 truncate max-w-[120px]">
            {{ $task->project->project_code ?? 'PRJ' }}
        </span>

        @if($task->priority === 'urgent')
            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-rose-500/10 text-rose-400 border border-rose-500/30">Urgent</span>
        @elseif($task->priority === 'high')
            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-amber-500/10 text-amber-400 border border-amber-500/30">High</span>
        @elseif($task->priority === 'medium')
            <span class="px-1.5 py-0.5 rounded text-[9px] font-medium bg-slate-800 text-slate-400">Medium</span>
        @endif
    </div>

    <!-- Title & Desc -->
    <div>
        <h4 class="font-bold text-white text-xs leading-snug group-hover:text-indigo-300 transition-colors">
            {{ $task->title }}
        </h4>
        @if($task->description)
            <p class="text-[11px] text-slate-400 mt-1 line-clamp-2 leading-relaxed">
                {{ $task->description }}
            </p>
        @endif
    </div>

    <!-- Hours Meter -->
    <div class="p-2 rounded-lg bg-slate-900/60 border border-slate-800/80 space-y-1">
        <div class="flex items-center justify-between text-[10px]">
            <span class="text-slate-400">Jam Kerja:</span>
            <span class="font-bold text-white">{{ $task->actual_hours }} / {{ $task->estimated_hours }} Jam</span>
        </div>
        <div class="w-full h-1 rounded-full bg-slate-800 overflow-hidden">
            @php
                $pct = $task->estimated_hours > 0 ? min(100, ($task->actual_hours / $task->estimated_hours) * 100) : 0;
            @endphp
            <div class="h-full rounded-full bg-indigo-500" style="width: {{ $pct }}%"></div>
        </div>
    </div>

    <!-- Footer: Assignee & Action -->
    <div class="pt-2 border-t border-slate-800/80 flex items-center justify-between text-[11px]">
        <div class="flex items-center gap-1.5 text-slate-300 truncate max-w-[140px]">
            <div class="w-5 h-5 rounded-full bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center font-bold text-white text-[9px] shrink-0">
                {{ strtoupper(substr($task->assignedUser->name ?? 'U', 0, 1)) }}
            </div>
            <span class="truncate">{{ $task->assignedUser->name ?? 'Unassigned' }}</span>
        </div>

        <!-- Quick Status Change Form -->
        <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <select name="status" onchange="this.form.submit()" class="px-1.5 py-0.5 rounded bg-slate-900 border border-slate-700/80 text-[10px] text-slate-300 focus:outline-none focus:border-indigo-500">
                <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="review" {{ $task->status === 'review' ? 'selected' : '' }}>Review</option>
                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
            </select>
        </form>
    </div>

</div>

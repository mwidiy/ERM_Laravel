@extends('layouts.app')

@section('title', 'Katalog Layanan & Rate Cards')
@section('page_title', 'Katalog Layanan & Standar Tarif Jasa')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-800">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Katalog Layanan & Rate Cards</h1>
            <p class="text-sm text-slate-400 mt-1">Standar paket keahlian dan tarif acuan penagihan jasa konsultasi korporasi.</p>
        </div>
        <button onclick="openServiceModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-600/30 transition-all">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            <span>+ Tambah Layanan Baru</span>
        </button>
    </div>

    <!-- Services Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($services as $svc)
            <div class="rounded-2xl bg-slate-900/90 border border-slate-800 hover:border-indigo-500/40 transition-all p-6 flex flex-col justify-between shadow-xl group">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="text-xs font-mono font-bold px-2 py-0.5 rounded bg-slate-800 text-indigo-400 border border-slate-700/60">{{ $svc->service_code }}</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-indigo-500/10 text-indigo-300 border border-indigo-500/20">
                            {{ $svc->category }}
                        </span>
                    </div>

                    <h2 class="text-base font-bold text-white group-hover:text-indigo-400 transition-colors">
                        {{ $svc->name }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-2 line-clamp-3 leading-relaxed">
                        {{ $svc->description ?: 'Layanan konsultasi dan implementasi teknis profesional berstandar enterprise.' }}
                    </p>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-800/80 flex items-center justify-between">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase tracking-wider">Tarif Acuan (Rate Card)</span>
                        <span class="text-base font-black text-emerald-400">
                            Rp {{ number_format($svc->default_rate, 0, ',', '.') }}
                        </span>
                        <span class="text-[10px] text-slate-400">
                            / {{ $svc->billing_type === 'hourly' ? 'Jam Kerja' : ($svc->billing_type === 'monthly_retainer' ? 'Bulan' : 'Paket Selesai') }}
                        </span>
                    </div>

                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                        Aktif
                    </span>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400">
                Belum ada data layanan terdaftar.
            </div>
        @endforelse
    </div>

</div>

<!-- Modal Tambah Layanan -->
<div id="service-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl p-6 space-y-5">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-base font-bold text-white">Tambah Paket Layanan Baru</h3>
            <button onclick="closeServiceModal()" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form action="{{ route('services.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Kode Layanan <span class="text-rose-400">*</span></label>
                <input type="text" name="service_code" placeholder="Contoh: SVC-DEV-05" required
                       class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm font-mono text-indigo-400 focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Nama Layanan / Keahlian <span class="text-rose-400">*</span></label>
                <input type="text" name="name" placeholder="Contoh: DevOps & Continuous Delivery Pipeline" required
                       class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Kategori Layanan <span class="text-rose-400">*</span></label>
                <input type="text" name="category" placeholder="Contoh: Cloud & Infrastructure" required
                       class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Model Penagihan</label>
                    <select name="billing_type" class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="hourly">Per Jam (Hourly)</option>
                        <option value="fixed">Fixed Price</option>
                        <option value="monthly_retainer">Bulanan (Retainer)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Tarif Acuan (Rp) <span class="text-rose-400">*</span></label>
                    <input type="number" name="default_rate" placeholder="250000" min="0" step="10000" required
                           class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white font-semibold text-emerald-400 focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">Deskripsi Layanan</label>
                <textarea name="description" rows="2" placeholder="Uraian ruang lingkup keahlian dan deliverables..."
                          class="w-full px-3.5 py-2 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                <button type="button" onclick="closeServiceModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30">Simpan Layanan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openServiceModal() {
        const modal = document.getElementById('service-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeServiceModal() {
        const modal = document.getElementById('service-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
@endsection

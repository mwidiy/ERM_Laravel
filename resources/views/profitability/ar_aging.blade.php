@extends('layouts.app')

@section('page_title', 'Jadwal Umur Piutang Usaha (AR Aging Schedule)')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('invoices.index') }}" class="hover:text-white transition-colors">Faktur & Piutang</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-600"></i>
                <span class="text-white">Analisis Kolektibilitas</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">
                <span class="w-2.5 h-6 bg-amber-500 rounded-full inline-block"></span>
                Jadwal Umur Piutang Usaha (AR Aging Schedule)
            </h1>
            <p class="text-sm text-slate-400 mt-1">Klasifikasi risiko umur piutang faktur klien untuk mitigasi arus kas dan percepatan penagihan.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('invoices.index') }}" class="px-3.5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs font-semibold border border-slate-700/80 transition-all">
                <i class="fa-solid fa-file-invoice mr-1.5"></i> Daftar Faktur
            </a>
            <a href="{{ route('profitability.index') }}" class="px-3.5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold transition-all">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Laba Rugi Proyek
            </a>
        </div>
    </div>

    <!-- 5 Aging Bucket KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
        <!-- Total AR -->
        <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Piutang (AR)</div>
            <div class="mt-1.5 text-xl font-black text-white">Rp {{ number_format($totalAR, 0, ',', '.') }}</div>
            <div class="mt-1 text-[11px] text-slate-400 font-medium">Semua saldo belum lunas</div>
        </div>

        <!-- Current (Lancar) -->
        <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-400">Belum Jatuh Tempo</div>
            <div class="mt-1.5 text-xl font-black text-emerald-400">Rp {{ number_format($agingBuckets['current'], 0, ',', '.') }}</div>
            <div class="mt-1 text-[11px] text-emerald-300/80 font-medium">Status Lancar (Current)</div>
        </div>

        <!-- 1 - 30 Days Overdue -->
        <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-[11px] font-bold uppercase tracking-wider text-amber-400">1 - 30 Hari Overdue</div>
            <div class="mt-1.5 text-xl font-black text-amber-400">Rp {{ number_format($agingBuckets['1_30'], 0, ',', '.') }}</div>
            <div class="mt-1 text-[11px] text-amber-300/80 font-medium">Perlu pengingat penagihan</div>
        </div>

        <!-- 31 - 60 Days Overdue -->
        <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-[11px] font-bold uppercase tracking-wider text-orange-400">31 - 60 Hari Overdue</div>
            <div class="mt-1.5 text-xl font-black text-orange-400">Rp {{ number_format($agingBuckets['31_60'], 0, ',', '.') }}</div>
            <div class="mt-1 text-[11px] text-orange-300/80 font-medium">Surat peringatan ke-1</div>
        </div>

        <!-- > 60 Days Overdue -->
        <div class="p-4 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm relative overflow-hidden">
            <div class="text-[11px] font-bold uppercase tracking-wider text-rose-400">> 60 Hari (Kritis)</div>
            <div class="mt-1.5 text-xl font-black text-rose-400">Rp {{ number_format($agingBuckets['61_90'] + $agingBuckets['over_90'], 0, ',', '.') }}</div>
            <div class="mt-1 text-[11px] text-rose-300/80 font-medium">Risiko tinggi / Macet</div>
        </div>
    </div>

    <!-- Client Aging Matrix Table -->
    <div class="rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-table-cells text-amber-400"></i>
                    Matriks Umur Piutang per Klien Korporasi
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Sebaran kewajiban pembayaran klien berdasarkan lamanya tunggakan</p>
            </div>
            <span class="text-xs font-semibold text-slate-400">{{ count($clientAging) }} Klien dengan Piutang Beredar</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs uppercase bg-slate-950/70 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold">Nama Klien Korporasi</th>
                        <th class="px-5 py-3.5 font-semibold text-right text-emerald-400">Lancar (Current)</th>
                        <th class="px-5 py-3.5 font-semibold text-right text-amber-400">1 - 30 Hari</th>
                        <th class="px-5 py-3.5 font-semibold text-right text-orange-400">31 - 60 Hari</th>
                        <th class="px-5 py-3.5 font-semibold text-right text-rose-400">> 60 Hari</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Total Piutang</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Tingkat Risiko</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($clientAging as $ca)
                        <tr class="hover:bg-slate-850/40 transition-colors">
                            <td class="px-5 py-4">
                                <a href="{{ route('clients.show', $ca->client->id) }}" class="font-bold text-white hover:text-indigo-400 transition-colors">
                                    {{ $ca->client->company_name }}
                                </a>
                                <div class="text-[11px] text-slate-400 mt-0.5">{{ $ca->client->city }} • Syarat: {{ $ca->client->payment_terms_days ?? 30 }} Hari</div>
                            </td>
                            <td class="px-5 py-4 text-right font-mono {{ $ca->current > 0 ? 'text-emerald-400 font-semibold' : 'text-slate-600' }}">
                                Rp {{ number_format($ca->current, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono {{ $ca->{'1_30'} > 0 ? 'text-amber-400 font-bold' : 'text-slate-600' }}">
                                Rp {{ number_format($ca->{'1_30'}, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono {{ $ca->{'31_60'} > 0 ? 'text-orange-400 font-bold' : 'text-slate-600' }}">
                                Rp {{ number_format($ca->{'31_60'}, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono {{ ($ca->{'61_90'} + $ca->over_90) > 0 ? 'text-rose-400 font-bold' : 'text-slate-600' }}">
                                Rp {{ number_format($ca->{'61_90'} + $ca->over_90, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-bold text-white">
                                Rp {{ number_format($ca->total_ar, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                @if(($ca->{'61_90'} + $ca->over_90) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                        <i class="fa-solid fa-triangle-exclamation mr-1.5 text-[9px]"></i> Kritis
                                    </span>
                                @elseif(($ca->{'1_30'} + $ca->{'31_60'}) > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        <i class="fa-solid fa-clock mr-1.5 text-[9px]"></i> Perhatian
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <i class="fa-solid fa-circle-check mr-1.5 text-[9px]"></i> Lancar
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-circle-check text-3xl mb-3 text-emerald-500/50 block"></i>
                                Semua piutang klien telah lunas terbayar! Arus kas dalam kondisi optimal.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Outstanding Invoices List -->
    <div class="rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-800 flex items-center justify-between">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <i class="fa-solid fa-receipt text-indigo-400"></i>
                Rincian Faktur Belum Lunas & Status Keterlambatan
            </h3>
            <span class="text-xs text-slate-400">Per Tanggal: {{ date('d F Y') }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs uppercase bg-slate-950/70 text-slate-400 border-b border-slate-800">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold">Nomor Faktur</th>
                        <th class="px-5 py-3.5 font-semibold">Klien & Proyek</th>
                        <th class="px-5 py-3.5 font-semibold">Jatuh Tempo</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Status Umur</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Nilai Faktur</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Sisa Piutang</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($clientAging as $ca)
                        @foreach($ca->invoices as $invItem)
                            @php $inv = $invItem->invoice; @endphp
                            <tr class="hover:bg-slate-850/40 transition-colors">
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <a href="{{ route('invoices.show', $inv->id) }}" class="font-mono text-xs font-bold text-indigo-400 hover:underline">
                                        {{ $inv->invoice_number }}
                                    </a>
                                    <div class="text-[10px] text-slate-500 mt-0.5">Terbit: {{ $inv->issue_date->format('d M Y') }}</div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-semibold text-white">{{ $inv->client->company_name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $inv->project->name }}</div>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <div class="text-xs font-medium text-slate-200">{{ $inv->due_date->format('d M Y') }}</div>
                                    @if($invItem->days_diff < 0)
                                        <div class="text-[10px] text-rose-400 font-bold">Terlambat {{ abs($invItem->days_diff) }} hari</div>
                                    @elseif($invItem->days_diff == 0)
                                        <div class="text-[10px] text-amber-400 font-bold">Jatuh tempo hari ini</div>
                                    @else
                                        <div class="text-[10px] text-slate-500">{{ $invItem->days_diff }} hari lagi</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    @if($invItem->bucket === 'current')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Lancar</span>
                                    @elseif($invItem->bucket === '1_30')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">1 - 30 Hari</span>
                                    @elseif($invItem->bucket === '31_60')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-orange-500/10 text-orange-400 border border-orange-500/20">31 - 60 Hari</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-400 border border-rose-500/20">> 60 Hari</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono text-white whitespace-nowrap">
                                    Rp {{ number_format($inv->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-amber-400 whitespace-nowrap">
                                    Rp {{ number_format($inv->remaining_balance, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1">
                                    <a href="{{ route('invoices.show', $inv->id) }}" class="p-1.5 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 border border-indigo-500/30 inline-block text-xs" title="Buka Faktur & Catat Pembayaran">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-6 text-center text-slate-500 text-xs">
                                Tidak ada tagihan overdue yang perlu tindakan penagihan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

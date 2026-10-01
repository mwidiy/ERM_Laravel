@extends('layouts.app')

@section('page_title', 'Detail Faktur: ' . $invoice->invoice_number)

@section('content')
<div class="space-y-6">

    <!-- Header & Action Ribbon -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('invoices.index') }}" class="hover:text-white transition-colors">Faktur & Piutang</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-600"></i>
                <span class="font-mono text-indigo-400">{{ $invoice->invoice_number }}</span>
            </div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-white tracking-tight font-mono">{{ $invoice->invoice_number }}</h1>
                {!! $invoice->status_badge !!}
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Print / PDF Button -->
            <a href="{{ route('invoices.print', $invoice->id) }}" target="_blank" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-200 text-xs font-semibold border border-slate-700/80 transition-all">
                <i class="fa-solid fa-print"></i>
                <span>Cetak / Unduh PDF</span>
            </a>

            <!-- Mark as Sent Button -->
            @if($invoice->status === 'draft')
                <form method="POST" action="{{ route('invoices.send', $invoice->id) }}" class="inline-block">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 border border-blue-500/30 text-xs font-semibold transition-all">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>Tandai Terkirim</span>
                    </button>
                </form>
            @endif

            <!-- Record Payment Button -->
            @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
                <button onclick="document.getElementById('modal-record-payment').classList.remove('hidden')" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shadow-lg shadow-emerald-600/30 transition-all active:scale-95">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    <span>Catat Pembayaran AR</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Invoice Body, Line Items & Taxes -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Client & Project Meta Card -->
            <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Ditagihkan Kepada:</div>
                        <div class="text-base font-bold text-white">{{ $invoice->client->company_name }}</div>
                        <div class="text-xs text-slate-400 mt-1">
                            {{ $invoice->client->address ?? 'Alamat belum diatur' }}<br>
                            {{ $invoice->client->city ?? '' }}
                        </div>
                        @if($invoice->client->tax_id)
                            <div class="text-xs text-indigo-400 font-mono mt-2">
                                <span class="text-slate-500">NPWP:</span> {{ $invoice->client->tax_id }}
                            </div>
                        @endif
                    </div>

                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Referensi Proyek & Termin:</div>
                        <a href="{{ route('projects.show', $invoice->project_id) }}" class="text-base font-bold text-indigo-400 hover:underline">
                            {{ $invoice->project->name }}
                        </a>
                        <div class="text-xs text-slate-400 mt-1 font-mono">Kode: {{ $invoice->project->project_code }}</div>
                        @if($invoice->milestone)
                            <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-500/10 text-indigo-300 text-xs border border-indigo-500/20 font-medium">
                                <i class="fa-solid fa-flag-checkered text-[10px]"></i>
                                Termin: {{ $invoice->milestone->title }} ({{ $invoice->milestone->billing_percentage }}%)
                            </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-slate-800/80 text-xs">
                    <div>
                        <div class="text-slate-400">Tanggal Terbit</div>
                        <div class="font-semibold text-white mt-0.5">{{ $invoice->issue_date->format('d M Y') }}</div>
                    </div>
                    <div>
                        <div class="text-slate-400">Jatuh Tempo</div>
                        <div class="font-semibold text-white mt-0.5">{{ $invoice->due_date->format('d M Y') }}</div>
                    </div>
                    <div>
                        <div class="text-slate-400">Syarat Pembayaran</div>
                        <div class="font-semibold text-white mt-0.5">{{ $invoice->payment_terms ?? 'Net 30 Hari' }}</div>
                    </div>
                    <div>
                        <div class="text-slate-400">Dibuat Oleh</div>
                        <div class="font-semibold text-white mt-0.5">{{ $invoice->creator->name ?? 'System' }}</div>
                    </div>
                </div>
            </div>

            <!-- Line Items Table -->
            <div class="rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-indigo-400"></i> Rincian Penagihan Layanan
                    </h2>
                    <span class="text-xs text-slate-400">{{ $invoice->items->count() }} Item</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="text-xs uppercase bg-slate-950/70 text-slate-400 border-b border-slate-800">
                            <tr>
                                <th class="px-6 py-3.5 font-semibold">Deskripsi Layanan</th>
                                <th class="px-6 py-3.5 font-semibold text-center w-24">Qty</th>
                                <th class="px-6 py-3.5 font-semibold text-center w-24">Satuan</th>
                                <th class="px-6 py-3.5 font-semibold text-right w-40">Harga Satuan</th>
                                <th class="px-6 py-3.5 font-semibold text-right w-44">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-white">{{ $item->item_description }}</td>
                                    <td class="px-6 py-4 text-center">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center text-slate-400">{{ $item->unit }}</td>
                                    <td class="px-6 py-4 text-right font-mono">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-right font-bold font-mono text-white">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Financial Calculation Breakdown -->
                <div class="p-6 bg-slate-950/70 border-t border-slate-800 space-y-3">
                    <div class="flex justify-between text-sm text-slate-400">
                        <span>Dasar Pengenaan Pajak (DPP / Subtotal):</span>
                        <span class="font-mono font-semibold text-white">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-400">
                        <span>Pajak Pertambahan Nilai (PPN {{ $invoice->tax_rate }}%):</span>
                        <span class="font-mono font-semibold text-emerald-400">+ Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-400">
                        <span>Potongan PPh Pasal 23 Jasa ({{ $invoice->withholding_tax_rate }}%):</span>
                        <span class="font-mono font-semibold text-rose-400">- Rp {{ number_format($invoice->withholding_tax_amount, 0, ',', '.') }}</span>
                    </div>
                    @if($invoice->discount_amount > 0)
                        <div class="flex justify-between text-sm text-slate-400">
                            <span>Diskon / Potongan Khusus:</span>
                            <span class="font-mono font-semibold text-slate-300">- Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="pt-3 border-t border-slate-800 flex justify-between items-center">
                        <span class="text-base font-bold text-white">Total Tagihan Bersih (Grand Total):</span>
                        <span class="text-2xl font-extrabold font-mono text-indigo-400">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Bank Information & Notes -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm text-xs">
                <div>
                    <div class="font-bold uppercase tracking-wider text-slate-400 mb-1">Instruksi Pembayaran Bank</div>
                    <div class="font-mono text-slate-300 whitespace-pre-line leading-relaxed">{{ $invoice->bank_details ?? '-' }}</div>
                </div>
                <div>
                    <div class="font-bold uppercase tracking-wider text-slate-400 mb-1">Catatan Faktur</div>
                    <div class="text-slate-300 leading-relaxed">{{ $invoice->notes ?? 'Tidak ada catatan tambahan.' }}</div>
                </div>
            </div>

        </div>

        <!-- Right 1 Col: AR Tracking, Payment History & Progress -->
        <div class="space-y-6">

            <!-- AR Status Card -->
            <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm space-y-4">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Piutang (AR Balance)</div>
                
                <div class="space-y-1">
                    <div class="text-xs text-slate-400">Sisa Saldo Belum Tertagih:</div>
                    <div class="text-3xl font-extrabold font-mono {{ $invoice->remaining_balance > 0 ? 'text-amber-400' : 'text-emerald-400' }}">
                        Rp {{ number_format($invoice->remaining_balance, 0, ',', '.') }}
                    </div>
                </div>

                @php
                    $pct = $invoice->total_amount > 0 ? min(100, round(($invoice->paid_amount / $invoice->total_amount) * 100)) : 0;
                @endphp
                <div class="space-y-1.5 pt-2">
                    <div class="flex justify-between text-xs font-medium">
                        <span class="text-slate-400">Progress Pembayaran</span>
                        <span class="text-white font-bold">{{ $pct }}%</span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <div class="flex justify-between text-[11px] text-slate-500 pt-1">
                        <span>Terbayar: Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</span>
                        <span>Total: Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($invoice->remaining_balance > 0)
                    <button onclick="document.getElementById('modal-record-payment').classList.remove('hidden')" 
                            class="w-full mt-3 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shadow-md shadow-emerald-600/30 transition-all text-center block">
                        <i class="fa-solid fa-plus-circle mr-1.5"></i> Catat Kas Masuk Sekarang
                    </button>
                @else
                    <div class="mt-3 p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs text-center font-semibold">
                        <i class="fa-solid fa-circle-check mr-1.5"></i> Tagihan ini telah Lunas Terbayar!
                    </div>
                @endif
            </div>

            <!-- Payment Transaction History -->
            <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300 flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-emerald-400"></i> Riwayat Kas Masuk
                    </h3>
                    <span class="text-[11px] font-semibold text-slate-500">{{ $invoice->payments->count() }} Pembayaran</span>
                </div>

                <div class="space-y-3">
                    @forelse($invoice->payments as $pay)
                        <div class="p-3 rounded-xl bg-slate-950 border border-slate-800 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-mono text-xs font-bold text-emerald-400">{{ $pay->payment_number }}</span>
                                <span class="text-[11px] text-slate-500">{{ $pay->payment_date->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-baseline justify-between">
                                <span class="text-xs text-slate-400">{{ $pay->method_label }}</span>
                                <span class="text-sm font-bold font-mono text-white">Rp {{ number_format($pay->amount, 0, ',', '.') }}</span>
                            </div>
                            @if($pay->reference_number)
                                <div class="text-[11px] text-slate-500 font-mono">Ref: {{ $pay->reference_number }}</div>
                            @endif
                            @if($pay->notes)
                                <div class="text-[11px] text-slate-400 italic">"{{ $pay->notes }}"</div>
                            @endif

                            <div class="flex items-center justify-between pt-1 border-t border-slate-900 text-[10px] text-slate-500">
                                <span>Oleh: {{ $pay->recorder->name ?? 'Admin' }}</span>
                                <form method="POST" action="{{ route('payments.destroy', $pay->id) }}" onsubmit="return confirm('Hapus catatan pembayaran ini? Saldo piutang akan disesuaikan kembali.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:text-rose-300">
                                        <i class="fa-solid fa-trash-can mr-1"></i> Batal Catatan
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-slate-500">
                            Belum ada riwayat pembayaran yang dicatat.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Modal: Record AR Payment -->
<div id="modal-record-payment" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm hidden">
    <div class="w-full max-w-md rounded-2xl bg-slate-900 border border-slate-800 p-6 shadow-2xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <h3 class="text-base font-bold text-white flex items-center gap-2">
                <i class="fa-solid fa-money-bill-wave text-emerald-400"></i>
                Catat Pelunasan Pembayaran AR
            </h3>
            <button onclick="document.getElementById('modal-record-payment').classList.add('hidden')" class="text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="p-3 rounded-xl bg-slate-950 border border-slate-800/80 text-xs space-y-1">
            <div class="text-slate-400">Nomor Faktur: <span class="font-mono font-bold text-white">{{ $invoice->invoice_number }}</span></div>
            <div class="text-slate-400">Sisa Saldo Piutang: <span class="font-mono font-bold text-amber-400">Rp {{ number_format($invoice->remaining_balance, 0, ',', '.') }}</span></div>
        </div>

        <form method="POST" action="{{ route('invoices.payments.store', $invoice->id) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Tanggal Pembayaran *</label>
                <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nominal Pelunasan (Rp) *</label>
                <input type="number" name="amount" min="1000" max="{{ $invoice->remaining_balance }}" step="1000" required value="{{ $invoice->remaining_balance }}" class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm font-mono font-bold text-emerald-400 focus:outline-none focus:border-emerald-500">
                <span class="text-[10px] text-slate-500 mt-1 block">Otomatis diisi sisa saldo piutang (bisa diubah untuk cicilan/termin parsial).</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Metode Pembayaran *</label>
                <select name="payment_method" required class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-emerald-500">
                    <option value="bank_transfer">Transfer Bank (Mandiri / BCA / BNI)</option>
                    <option value="check_giro">Cek / Bilyet Giro</option>
                    <option value="credit_card">Kartu Kredit Perusahaan</option>
                    <option value="cash">Kas Tunai</option>
                    <option value="other">Metode Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nomor Referensi Transfer / Cek</label>
                <input type="text" name="reference_number" placeholder="Contoh: TRF-MDR-20261001-9876" class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm font-mono text-slate-200 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Upload Bukti Transfer (Opsional)</label>
                <input type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf" class="w-full px-3 py-1.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-400 file:mr-3 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-xs file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Catatan Pembayaran</label>
                <textarea name="notes" rows="2" placeholder="Pelunasan termin 1 via transfer Mandiri..." class="w-full px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-emerald-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-800">
                <button type="button" onclick="document.getElementById('modal-record-payment').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-md shadow-emerald-600/30 transition-all">
                    Simpan Kas Masuk
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@extends('layouts.app')

@section('page_title', 'Terbitkan Faktur Baru')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header & Back Navigation -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('invoices.index') }}" class="hover:text-white transition-colors">Faktur & Piutang</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-600"></i>
                <span class="text-white">Penerbitan Faktur Termin</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Terbitkan Faktur Tagihan</h1>
        </div>
        <a href="{{ route('invoices.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs font-semibold border border-slate-800 transition-all">
            <i class="fa-solid fa-arrow-left mr-1.5"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Main Creation Form -->
    <form id="invoice-form" method="POST" action="{{ route('invoices.store') }}" class="space-y-6">
        @csrf

        <!-- General Info Card -->
        <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm space-y-5">
            <h2 class="text-sm font-bold uppercase tracking-wider text-indigo-400 flex items-center gap-2">
                <i class="fa-solid fa-file-invoice"></i> Informasi Dasar Penagihan
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Nomor Faktur (Invoice #) *</label>
                    <input type="text" name="invoice_number" required value="{{ old('invoice_number', $suggestedInvoiceNumber) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm font-mono font-bold text-indigo-400 focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Tanggal Terbit *</label>
                    <input type="date" name="issue_date" id="issue_date" required value="{{ old('issue_date', date('Y-m-d')) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Jatuh Tempo (Due Date) *</label>
                    <input type="date" name="due_date" id="due_date" required value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Pilih Proyek & Klien *</label>
                    <select name="project_id" id="project_id" required onchange="handleProjectChange(this)" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                        <option value="">-- Pilih Proyek --</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" 
                                    data-client="{{ $p->client->company_name }}" 
                                    data-milestones='@json($p->milestones)'
                                    {{ (old('project_id', $prefillProject->id ?? null) == $p->id) ? 'selected' : '' }}>
                                {{ $p->project_code }} - {{ $p->name }} ({{ $p->client->company_name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Tautkan ke Termin Milestone (Opsional)</label>
                    <select name="milestone_id" id="milestone_id" onchange="handleMilestoneChange(this)" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                        <option value="">-- Tanpa Termin (Tagihan Bebas / Custom) --</option>
                        @if($prefillProject)
                            @foreach($prefillProject->milestones as $m)
                                <option value="{{ $m->id }}" 
                                        data-amount="{{ $m->billing_amount }}" 
                                        data-title="{{ $m->title }}"
                                        {{ (old('milestone_id', $prefillMilestone->id ?? null) == $m->id) ? 'selected' : '' }}>
                                    {{ $m->title }} ({{ $m->billing_percentage }}% - Rp {{ number_format($m->billing_amount, 0, ',', '.') }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Term of Payment (Syarat Pembayaran)</label>
                    <input type="text" name="payment_terms" value="{{ old('payment_terms', 'Net 30 Hari') }}" placeholder="Contoh: Net 30 Hari / DP 50% di muka" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Diskon / Potongan Khusus (Rp)</label>
                    <input type="number" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', 0) }}" min="0" step="1000" oninput="calculateTotals()" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                </div>
            </div>
        </div>

        <!-- Line Items Card -->
        <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-800">
                <h2 class="text-sm font-bold uppercase tracking-wider text-indigo-400 flex items-center gap-2">
                    <i class="fa-solid fa-list-check"></i> Rincian Item Penagihan (Line Items)
                </h2>
                <button type="button" onclick="addRow()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 border border-indigo-500/30 text-xs font-semibold transition-colors">
                    <i class="fa-solid fa-plus text-[10px]"></i> Tambah Baris
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm" id="items-table">
                    <thead class="text-xs uppercase bg-slate-950/60 text-slate-400">
                        <tr>
                            <th class="p-3 font-semibold">Deskripsi Layanan / Pekerjaan</th>
                            <th class="p-3 font-semibold w-24">Qty</th>
                            <th class="p-3 font-semibold w-28">Satuan</th>
                            <th class="p-3 font-semibold w-44 text-right">Harga Satuan (Rp)</th>
                            <th class="p-3 font-semibold w-44 text-right">Total (Rp)</th>
                            <th class="p-3 font-semibold w-12 text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="items-body" class="divide-y divide-slate-800/60">
                        <!-- Initial Row -->
                        <tr class="item-row">
                            <td class="p-2">
                                <input type="text" name="items[0][description]" id="row-desc-0" required placeholder="Contoh: Termin 1 - Desain Arsitektur & SRS" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                            </td>
                            <td class="p-2">
                                <input type="number" name="items[0][quantity]" id="row-qty-0" value="1" min="0.1" step="0.1" required oninput="updateRowTotal(0)" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500 text-center">
                            </td>
                            <td class="p-2">
                                <input type="text" name="items[0][unit]" id="row-unit-0" value="Paket" required class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500 text-center">
                            </td>
                            <td class="p-2">
                                <input type="number" name="items[0][unit_price]" id="row-price-0" value="{{ $prefillMilestone->billing_amount ?? 15000000 }}" min="0" step="1000" required oninput="updateRowTotal(0)" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500 text-right">
                            </td>
                            <td class="p-2 text-right font-bold text-white">
                                <span id="row-total-display-0">Rp 15.000.000</span>
                            </td>
                            <td class="p-2 text-center">
                                <button type="button" onclick="removeRow(this)" class="p-1.5 text-slate-500 hover:text-rose-400 transition-colors">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Tax Configuration & Financial Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-800">
                <!-- Tax Rates Settings -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-300">Pengaturan Pajak Indonesia</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Tarif PPN (%)</label>
                            <input type="number" name="tax_rate" id="tax_rate" value="{{ old('tax_rate', 11.00) }}" step="0.5" min="0" max="100" oninput="calculateTotals()" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                            <span class="text-[10px] text-slate-500 mt-0.5 block">Standar PPN B2B: 11%</span>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-400 mb-1">Potongan PPh 23 (%)</label>
                            <input type="number" name="withholding_tax_rate" id="withholding_tax_rate" value="{{ old('withholding_tax_rate', 2.00) }}" step="0.5" min="0" max="100" oninput="calculateTotals()" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
                            <span class="text-[10px] text-slate-500 mt-0.5 block">PPh 23 Jasa: 2%</span>
                        </div>
                    </div>
                </div>

                <!-- Financial Calculation Card -->
                <div class="p-4 rounded-xl bg-slate-950 border border-slate-800/80 space-y-2.5 text-sm">
                    <div class="flex justify-between text-slate-400">
                        <span>Dasar Pengenaan Pajak (DPP / Subtotal):</span>
                        <span id="display-subtotal" class="font-semibold text-white">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>PPN (11%):</span>
                        <span id="display-vat" class="font-semibold text-emerald-400">+ Rp 0</span>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>Potongan PPh 23 Jasa (2%):</span>
                        <span id="display-wht" class="font-semibold text-rose-400">- Rp 0</span>
                    </div>
                    <div class="flex justify-between text-slate-400">
                        <span>Diskon / Potongan Khusus:</span>
                        <span id="display-discount" class="font-semibold text-slate-300">- Rp 0</span>
                    </div>
                    <div class="pt-2 border-t border-slate-800 flex justify-between items-center">
                        <span class="text-base font-bold text-white">Grand Total Tagihan:</span>
                        <span id="display-grand-total" class="text-xl font-extrabold text-indigo-400">Rp 0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Details & Notes -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-6 rounded-2xl bg-slate-900/60 border border-slate-800/80 shadow-sm">
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Informasi Rekening Bank Tujuan Transfer</label>
                <textarea name="bank_details" rows="3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500 font-mono text-xs">Bank Mandiri (Persero) Tbk
No. Rekening: 137-00-1928374-1
Atas Nama: PT ENTERPRISE SOLUSI INDONESIA
Cabang: Sudirman Trade Center Jakarta</textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1.5">Catatan Faktur untuk Klien</label>
                <textarea name="notes" rows="3" placeholder="Contoh: Bukti potong PPh 23 mohon dikirimkan via email finance@solusi.com..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500"></textarea>
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('invoices.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-semibold transition-colors">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold shadow-lg shadow-indigo-600/30 transition-all active:scale-95">
                <i class="fa-solid fa-paper-plane"></i>
                Simpan & Terbitkan Faktur
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
    let rowIndex = 1;

    function formatRupiah(number) {
        return 'Rp ' + Math.round(number).toLocaleString('id-ID');
    }

    function updateRowTotal(index) {
        const qty = parseFloat(document.getElementById(`row-qty-${index}`)?.value || 0);
        const price = parseFloat(document.getElementById(`row-price-${index}`)?.value || 0);
        const total = qty * price;
        const display = document.getElementById(`row-total-display-${index}`);
        if (display) {
            display.textContent = formatRupiah(total);
        }
        calculateTotals();
    }

    function addRow() {
        const tbody = document.getElementById('items-body');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td class="p-2">
                <input type="text" name="items[${rowIndex}][description]" id="row-desc-${rowIndex}" required placeholder="Deskripsi item pekerjaan..." class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500">
            </td>
            <td class="p-2">
                <input type="number" name="items[${rowIndex}][quantity]" id="row-qty-${rowIndex}" value="1" min="0.1" step="0.1" required oninput="updateRowTotal(${rowIndex})" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500 text-center">
            </td>
            <td class="p-2">
                <input type="text" name="items[${rowIndex}][unit]" id="row-unit-${rowIndex}" value="Paket" required class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500 text-center">
            </td>
            <td class="p-2">
                <input type="number" name="items[${rowIndex}][unit_price]" id="row-price-${rowIndex}" value="0" min="0" step="1000" required oninput="updateRowTotal(${rowIndex})" class="w-full px-3 py-2 rounded-lg bg-slate-950 border border-slate-800 text-sm text-slate-200 focus:outline-none focus:border-indigo-500 text-right">
            </td>
            <td class="p-2 text-right font-bold text-white">
                <span id="row-total-display-${rowIndex}">Rp 0</span>
            </td>
            <td class="p-2 text-center">
                <button type="button" onclick="removeRow(this)" class="p-1.5 text-slate-500 hover:text-rose-400 transition-colors">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        rowIndex++;
        calculateTotals();
    }

    function removeRow(button) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length <= 1) {
            alert('Faktur harus memiliki minimal 1 rincian item.');
            return;
        }
        button.closest('tr').remove();
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        const rows = document.querySelectorAll('.item-row');
        rows.forEach(r => {
            const qtyInput = r.querySelector('input[name*="[quantity]"]');
            const priceInput = r.querySelector('input[name*="[unit_price]"]');
            if (qtyInput && priceInput) {
                const qty = parseFloat(qtyInput.value || 0);
                const price = parseFloat(priceInput.value || 0);
                subtotal += (qty * price);
            }
        });

        const taxRate = parseFloat(document.getElementById('tax_rate').value || 0);
        const whtRate = parseFloat(document.getElementById('withholding_tax_rate').value || 0);
        const discount = parseFloat(document.getElementById('discount_amount').value || 0);

        const vatAmount = subtotal * (taxRate / 100);
        const whtAmount = subtotal * (whtRate / 100);
        const grandTotal = Math.max(0, (subtotal + vatAmount) - whtAmount - discount);

        document.getElementById('display-subtotal').textContent = formatRupiah(subtotal);
        document.getElementById('display-vat').textContent = '+ ' + formatRupiah(vatAmount);
        document.getElementById('display-wht').textContent = '- ' + formatRupiah(whtAmount);
        document.getElementById('display-discount').textContent = '- ' + formatRupiah(discount);
        document.getElementById('display-grand-total').textContent = formatRupiah(grandTotal);
    }

    function handleProjectChange(select) {
        const selectedOption = select.options[select.selectedIndex];
        const milestones = JSON.parse(selectedOption.getAttribute('data-milestones') || '[]');
        
        const milestoneSelect = document.getElementById('milestone_id');
        milestoneSelect.innerHTML = '<option value="">-- Tanpa Termin (Tagihan Bebas / Custom) --</option>';

        milestones.forEach(m => {
            const opt = document.createElement('option');
            opt.value = m.id;
            opt.setAttribute('data-amount', m.billing_amount);
            opt.setAttribute('data-title', m.title);
            opt.textContent = `${m.title} (${m.billing_percentage}% - Rp ${Math.round(m.billing_amount).toLocaleString('id-ID')})`;
            milestoneSelect.appendChild(opt);
        });
    }

    function handleMilestoneChange(select) {
        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption.value) return;

        const title = selectedOption.getAttribute('data-title');
        const amount = selectedOption.getAttribute('data-amount');

        const firstDesc = document.getElementById('row-desc-0');
        const firstPrice = document.getElementById('row-price-0');

        if (firstDesc && firstPrice) {
            firstDesc.value = `Pembayaran Termin: ${title}`;
            firstPrice.value = amount;
            updateRowTotal(0);
        }
    }

    // Inisialisasi kalkulasi saat load
    document.addEventListener('DOMContentLoaded', () => {
        updateRowTotal(0);
    });
</script>
@endpush
@endsection

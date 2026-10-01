@extends('layouts.app')

@section('title', 'Daftarkan Klien Baru')
@section('page_title', 'Registrasi Klien Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between pb-4 border-b border-slate-800">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Formulir Registrasi Klien Baru</h1>
            <p class="text-sm text-slate-400 mt-1">Daftarkan data legalitas perusahaan klien untuk penerbitan kontrak dan penagihan termin.</p>
        </div>
        <a href="{{ route('clients.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 text-xs font-semibold border border-slate-700/80 transition-all">
            &larr; Kembali ke Daftar Klien
        </a>
    </div>

    <!-- Form Container -->
    <form action="{{ route('clients.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Section 1: Profil & Legalitas Perusahaan -->
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 sm:p-8 space-y-6 shadow-xl">
            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    Informasi Legalitas & Profil Perusahaan
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Identitas resmi entitas perusahaan yang mengikat kontrak kerja sama.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Kode Klien (Auto-Generated) <span class="text-rose-400">*</span></label>
                    <input type="text" name="client_code" value="{{ old('client_code', $nextCode) }}" readonly
                           class="w-full px-4 py-2.5 bg-slate-950/80 border border-slate-800 rounded-xl text-sm font-mono text-indigo-400 font-bold focus:outline-none cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Perusahaan Klien <span class="text-rose-400">*</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Contoh: PT Telekomunikasi Nusantara Tbk" required
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Sektor Industri</label>
                    <input type="text" name="industry" value="{{ old('industry') }}" placeholder="Contoh: Banking & Financial Services"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nomor NPWP Perusahaan</label>
                    <input type="text" name="tax_id" value="{{ old('tax_id') }}" placeholder="Contoh: 01.234.567.8-012.000"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Email Resmi Kantor</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="info@perusahaan.co.id"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Telepon Kantor</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+62 21 5566 7788"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Website Perusahaan</label>
                    <input type="url" name="website" value="{{ old('website') }}" placeholder="https://perusahaan.co.id"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Alamat Lengkap Kantor</label>
                    <textarea name="address" rows="2" placeholder="Gedung, Lantai, Jalan, Kelurahan, Kecamatan"
                              class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">{{ old('address') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Kota / Wilayah</label>
                    <input type="text" name="city" value="{{ old('city', 'Jakarta') }}" placeholder="Jakarta Selatan"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Status Kemitraan</label>
                    <select name="status" class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white focus:outline-none focus:border-indigo-500">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif (Siap Menerima Proyek)</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Non-Aktif (Arsip)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 2: Ketentuan Finansial & Pembayaran -->
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 sm:p-8 space-y-6 shadow-xl">
            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Ketentuan Termin & Plafon Kredit (Credit Terms)
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Kebijakan jatuh tempo faktur dan batas limit nilai piutang terbuka untuk klien ini.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Term of Payment (Hari Jatuh Tempo) <span class="text-rose-400">*</span></label>
                    <div class="relative">
                        <input type="number" name="payment_terms_days" value="{{ old('payment_terms_days', 30) }}" min="0" required
                               class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                        <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-xs text-slate-400">Hari (Net)</span>
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">Standar B2B: Net 14 hari, Net 30 hari, atau Net 45 hari.</span>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Plafon Batas Piutang (Credit Limit)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-xs text-slate-400 font-bold">Rp</span>
                        <input type="number" name="credit_limit" value="{{ old('credit_limit', 500000000) }}" step="1000000" min="0"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                    </div>
                    <span class="text-[11px] text-slate-400 mt-1 block">Batas maksimal invoice belum terbayar sebelum peringatan audit.</span>
                </div>
            </div>
        </div>

        <!-- Section 3: Person In Charge (PIC) Utama -->
        <div class="rounded-2xl bg-slate-900/90 border border-slate-800 p-6 sm:p-8 space-y-6 shadow-xl">
            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-violet-500"></span>
                    Person In Charge (PIC) & Kontak Utama
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">Pejabat atau kontak perwakilan klien yang menandatangani BAST & faktur.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Lengkap PIC</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}" placeholder="Contoh: Hendra Gunawan"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Jabatan PIC</label>
                    <input type="text" name="contact_position" value="{{ old('contact_position') }}" placeholder="Contoh: Head of IT / Procurement Specialist"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Email PIC</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" placeholder="hendra@perusahaan.co.id"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nomor WhatsApp / HP PIC</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="081122334455"
                           class="w-full px-4 py-2.5 bg-slate-950 border border-slate-800 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500">
                </div>
            </div>
        </div>

        <!-- Submit & Actions -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('clients.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-slate-300 text-sm font-semibold border border-slate-700/80 transition-all">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold shadow-lg shadow-indigo-600/30 transition-all">
                Simpan & Daftarkan Klien
            </button>
        </div>
    </form>

</div>
@endsection

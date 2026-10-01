<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Penagihan - {{ $invoice->invoice_number }}</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background-color: #f1f5f9;
            font-size: 13px;
            line-height: 1.5;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .print-toolbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #4f46e5;
            color: white;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .btn-secondary {
            background: #334155;
            color: #e2e8f0;
        }

        .btn-secondary:hover {
            background: #475569;
        }

        .invoice-wrapper {
            max-width: 800px;
            margin: 90px auto 40px;
            background: #ffffff;
            padding: 45px 50px;
            border-radius: 4px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            position: relative;
        }

        /* Watermark Lunas */
        .watermark-paid {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            font-size: 80px;
            font-weight: 900;
            color: rgba(16, 185, 129, 0.12);
            border: 6px dashed rgba(16, 185, 129, 0.25);
            padding: 10px 40px;
            border-radius: 12px;
            text-transform: uppercase;
            pointer-events: none;
            letter-spacing: 6px;
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 25px;
            margin-bottom: 25px;
        }

        .company-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .logo-box {
            width: 40px;
            height: 40px;
            background: #4f46e5;
            color: white;
            font-weight: 800;
            font-size: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .company-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .company-sub {
            font-size: 11px;
            color: #64748b;
        }

        .invoice-title-block {
            text-align: right;
        }

        .invoice-title {
            font-size: 22px;
            font-weight: 800;
            color: #4f46e5;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .invoice-number {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            font-family: monospace;
            margin-top: 4px;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .meta-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px 20px;
        }

        .meta-card h4 {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 12px;
        }

        .meta-label {
            color: #64748b;
        }

        .meta-val {
            font-weight: 600;
            color: #0f172a;
        }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        table.items-table th {
            background: #f1f5f9;
            color: #334155;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 10px 14px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
        }

        table.items-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12px;
        }

        table.items-table tr:last-child td {
            border-bottom: 2px solid #cbd5e1;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-section {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .bank-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 14px 18px;
            font-size: 12px;
        }

        .bank-box h4 {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #4f46e5;
            margin-bottom: 8px;
        }

        .calc-table {
            width: 100%;
            font-size: 12px;
        }

        .calc-table td {
            padding: 4px 0;
        }

        .calc-table .grand-total td {
            padding-top: 10px;
            border-top: 2px solid #0f172a;
            font-size: 15px;
            font-weight: 800;
            color: #4f46e5;
        }

        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 40px;
            padding-top: 20px;
        }

        .sig-block {
            text-align: center;
        }

        .sig-space {
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stamp-lunas {
            border: 2px solid #10b981;
            color: #10b981;
            padding: 4px 14px;
            border-radius: 4px;
            font-weight: 800;
            font-size: 12px;
            display: inline-block;
            transform: rotate(-5deg);
        }

        .sig-name {
            font-weight: 700;
            color: #0f172a;
            border-top: 1px solid #94a3b8;
            padding-top: 4px;
            display: inline-block;
            min-width: 180px;
        }

        .sig-role {
            font-size: 11px;
            color: #64748b;
        }

        @media print {
            body {
                background: #ffffff;
            }
            .print-toolbar {
                display: none !important;
            }
            .invoice-wrapper {
                margin: 0 auto;
                padding: 20px 25px;
                box-shadow: none;
                max-width: 100%;
            }
            @page {
                size: A4;
                margin: 15mm;
            }
        }
    </style>
</head>
<body>

    <!-- Print / Export Bar -->
    <div class="print-toolbar">
        <div style="font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-file-invoice"></i>
            <span>Preview Faktur Resmi (Tax & Commercial Invoice)</span>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fa-solid fa-print"></i>
                <span>Cetak / Simpan PDF</span>
            </button>
            <button onclick="window.close()" class="btn btn-secondary">
                <i class="fa-solid fa-xmark"></i>
                <span>Tutup</span>
            </button>
        </div>
    </div>

    <!-- Printable Invoice Page -->
    <div class="invoice-wrapper">

        @if($invoice->status === 'paid')
            <div class="watermark-paid">LUNAS</div>
        @endif

        <!-- Header -->
        <div class="header-section">
            <div>
                <div class="company-logo">
                    <div class="logo-box">E</div>
                    <div>
                        <div class="company-title">PT ENTERPRISE SOLUSI INDONESIA</div>
                        <div class="company-sub">Information Technology & Enterprise Business Solutions</div>
                    </div>
                </div>
                <div style="font-size: 11px; color: #475569; line-height: 1.5;">
                    Sudirman Central Business District (SCBD) Lot 28, Tower A Lt. 18<br>
                    Jl. Jend. Sudirman Kav. 52-53, Senayan, Jakarta Selatan 12190<br>
                    <strong>NPWP:</strong> 01.345.678.9-012.000 | <strong>Email:</strong> billing@solusi.co.id
                </div>
            </div>

            <div class="invoice-title-block">
                <div class="invoice-title">FAKTUR PENAGIHAN</div>
                <div style="font-size: 11px; color: #64748b; text-transform: uppercase;">TAX & COMMERCIAL INVOICE</div>
                <div class="invoice-number">{{ $invoice->invoice_number }}</div>

                <div style="margin-top: 12px; font-size: 12px;">
                    <div style="color: #64748b;">Tanggal Terbit: <strong style="color: #0f172a;">{{ $invoice->issue_date->format('d/m/Y') }}</strong></div>
                    <div style="color: #64748b; margin-top: 2px;">Jatuh Tempo: <strong style="color: #e11d48;">{{ $invoice->due_date->format('d/m/Y') }}</strong></div>
                </div>
            </div>
        </div>

        <!-- Meta Grid -->
        <div class="meta-grid">
            <!-- Client Info -->
            <div class="meta-card">
                <h4>Ditagihkan Kepada (Billed To):</h4>
                <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">{{ $invoice->client->company_name }}</div>
                <div style="font-size: 11px; color: #475569; margin-bottom: 6px;">
                    {{ $invoice->client->address ?? 'Alamat operasional perusahaan' }}<br>
                    {{ $invoice->client->city ?? '' }}
                </div>
                <div class="meta-row">
                    <span class="meta-label">NPWP Klien:</span>
                    <span class="meta-val font-mono">{{ $invoice->client->tax_id ?? '-' }}</span>
                </div>
                @if($invoice->client->primaryContact)
                    <div class="meta-row">
                        <span class="meta-label">Up (PIC):</span>
                        <span class="meta-val">{{ $invoice->client->primaryContact->name }} ({{ $invoice->client->primaryContact->position }})</span>
                    </div>
                @endif
            </div>

            <!-- Project & Contract Info -->
            <div class="meta-card">
                <h4>Detail Kontrak & Proyek:</h4>
                <div style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">{{ $invoice->project->name }}</div>
                <div class="meta-row">
                    <span class="meta-label">Kode Proyek:</span>
                    <span class="meta-val font-mono">{{ $invoice->project->project_code }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Syarat Pembayaran:</span>
                    <span class="meta-val">{{ $invoice->payment_terms ?? 'Net 30 Hari' }}</span>
                </div>
                @if($invoice->milestone)
                    <div class="meta-row">
                        <span class="meta-label">Termin Milestone:</span>
                        <span class="meta-val">{{ $invoice->milestone->title }} ({{ $invoice->milestone->billing_percentage }}%)</span>
                    </div>
                @endif
                <div class="meta-row">
                    <span class="meta-label">Project Manager:</span>
                    <span class="meta-val">{{ $invoice->project->projectManager->name ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Line Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 35px;" class="text-center">No</th>
                    <th>Deskripsi Layanan / Pekerjaan</th>
                    <th class="text-center" style="width: 60px;">Qty</th>
                    <th class="text-center" style="width: 70px;">Satuan</th>
                    <th class="text-right" style="width: 130px;">Harga Satuan (Rp)</th>
                    <th class="text-right" style="width: 140px;">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $idx => $item)
                    <tr>
                        <td class="text-center" style="color: #64748b;">{{ $idx + 1 }}</td>
                        <td>
                            <strong style="color: #0f172a;">{{ $item->item_description }}</strong>
                        </td>
                        <td class="text-center">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                        <td class="text-center" style="color: #64748b;">{{ $item->unit }}</td>
                        <td class="text-right font-mono">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-right font-mono" style="font-weight: 700; color: #0f172a;">{{ number_format($item->total_price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary & Bank Info -->
        <div class="summary-section">
            <!-- Bank & Terms -->
            <div>
                <div class="bank-box">
                    <h4>Instruksi Transfer Bank:</h4>
                    <div style="font-family: monospace; line-height: 1.6; color: #334155; font-size: 11px;">
                        {!! nl2br(e($invoice->bank_details)) !!}
                    </div>
                    <div style="margin-top: 10px; font-size: 11px; color: #64748b; border-top: 1px dashed #cbd5e1; padding-top: 6px;">
                        <em>* Mohon mencantumkan Nomor Faktur <strong>{{ $invoice->invoice_number }}</strong> pada berita transfer dan kirimkan bukti setor ke finance@solusi.co.id</em>
                    </div>
                </div>
            </div>

            <!-- Tax & Calculation Breakdown -->
            <div>
                <table class="calc-table">
                    <tr>
                        <td class="meta-label">Dasar Pengenaan Pajak (DPP):</td>
                        <td class="text-right font-mono font-bold" style="color: #0f172a;">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">PPN ({{ $invoice->tax_rate }}%):</td>
                        <td class="text-right font-mono" style="color: #10b981;">+ Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="meta-label">Potongan PPh 23 ({{ $invoice->withholding_tax_rate }}%):</td>
                        <td class="text-right font-mono" style="color: #e11d48;">- Rp {{ number_format($invoice->withholding_tax_amount, 0, ',', '.') }}</td>
                    </tr>
                    @if($invoice->discount_amount > 0)
                        <tr>
                            <td class="meta-label">Potongan Diskon:</td>
                            <td class="text-right font-mono">- Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr class="grand-total">
                        <td>Total Bersih Tagihan:</td>
                        <td class="text-right font-mono">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Signatures & Official Stamp -->
        <div class="signature-section">
            <div class="sig-block">
                <div style="font-size: 11px; color: #64748b; margin-bottom: 4px;">Penerima / Klien,</div>
                <div style="font-weight: 600; font-size: 12px; color: #0f172a;">{{ $invoice->client->company_name }}</div>
                <div class="sig-space"></div>
                <div class="sig-name">( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; )</div>
                <div class="sig-role">Tanda Tangan & Cap Perusahaan</div>
            </div>

            <div class="sig-block">
                <div style="font-size: 11px; color: #64748b; margin-bottom: 4px;">Hormat Kami,</div>
                <div style="font-weight: 600; font-size: 12px; color: #0f172a;">PT ENTERPRISE SOLUSI INDONESIA</div>
                <div class="sig-space">
                    @if($invoice->status === 'paid')
                        <div class="stamp-lunas">
                            <i class="fa-solid fa-check-double"></i> LUNAS TERBAYAR
                        </div>
                    @endif
                </div>
                <div class="sig-name">Alexander Pratama, S.Kom., M.M.</div>
                <div class="sig-role">Managing Director / CFO</div>
            </div>
        </div>

    </div>

</body>
</html>

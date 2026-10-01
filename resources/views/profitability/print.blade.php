<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Eksekutif Laba Rugi Proyek - PT Enterprise Solusi Indonesia</title>
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
            color: #0f172a;
            background-color: #f8fafc;
            font-size: 12px;
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
            background: #059669;
            color: white;
        }

        .btn-primary:hover {
            background: #047857;
        }

        .btn-secondary {
            background: #334155;
            color: #e2e8f0;
        }

        .report-wrapper {
            max-width: 900px;
            margin: 90px auto 40px;
            background: #ffffff;
            padding: 45px 50px;
            border-radius: 4px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .company-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .logo-box {
            width: 38px;
            height: 38px;
            background: #059669;
            color: white;
            font-weight: 800;
            font-size: 18px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .company-title {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .report-title-block {
            text-align: right;
        }

        .report-title {
            font-size: 18px;
            font-weight: 800;
            color: #059669;
            text-transform: uppercase;
        }

        .report-period {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
        }

        .kpi-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            background: #f1f5f9;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }

        .kpi-item h5 {
            font-size: 10px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .kpi-item .val {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
        }

        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table.report-table th {
            background: #f8fafc;
            color: #334155;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 10px 10px;
            border-top: 1px solid #0f172a;
            border-bottom: 1px solid #0f172a;
        }

        table.report-table td {
            padding: 10px 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 11px;
        }

        table.report-table tr:last-child td {
            border-bottom: 2px solid #0f172a;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-mono {
            font-family: monospace;
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
            height: 70px;
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
            .report-wrapper {
                margin: 0 auto;
                padding: 15px 20px;
                box-shadow: none;
                max-width: 100%;
            }
            @page {
                size: A4 landscape;
                margin: 12mm;
            }
        }
    </style>
</head>
<body>

    <!-- Print / Export Bar -->
    <div class="print-toolbar">
        <div style="font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-chart-line text-emerald-400"></i>
            <span>Laporan Eksekutif Laba Rugi Proyek (Board of Directors Financial Statement)</span>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fa-solid fa-print"></i>
                <span>Cetak / Unduh PDF</span>
            </button>
            <button onclick="window.close()" class="btn btn-secondary">
                <i class="fa-solid fa-xmark"></i>
                <span>Tutup</span>
            </button>
        </div>
    </div>

    <!-- Printable Report Container -->
    <div class="report-wrapper">

        <!-- Corporate Header -->
        <div class="header-section">
            <div>
                <div class="company-logo">
                    <div class="logo-box">E</div>
                    <div>
                        <div class="company-title">PT ENTERPRISE SOLUSI INDONESIA</div>
                        <div style="font-size: 11px; color: #64748b;">Enterprise Business & Technology Consulting</div>
                    </div>
                </div>
                <div style="font-size: 11px; color: #475569;">
                    SCBD Lot 28, Tower A Lt. 18, Jakarta Selatan • Telp: (021) 529-8800
                </div>
            </div>

            <div class="report-title-block">
                <div class="report-title">LAPORAN PROFITABILITAS PROYEK</div>
                <div style="font-size: 11px; font-weight: 700; color: #0f172a; margin-top: 2px;">PROJECT GROSS PROFIT & MARGIN ANALYSIS</div>
                <div class="report-period">Dicetak Tanggal: <strong>{{ date('d F Y') }}</strong> • Periode Fiskal Q4 2026</div>
            </div>
        </div>

        <!-- KPI Strip -->
        <div class="kpi-strip">
            <div class="kpi-item">
                <h5>Nilai Kontrak Total</h5>
                <div class="val font-mono">Rp {{ number_format($portfolioContractValue, 0, ',', '.') }}</div>
            </div>
            <div class="kpi-item">
                <h5>Total Biaya Aktual Riil</h5>
                <div class="val font-mono" style="color: #0284c7;">Rp {{ number_format($portfolioTotalCost, 0, ',', '.') }}</div>
            </div>
            <div class="kpi-item">
                <h5>Laba Kotor Portofolio</h5>
                <div class="val font-mono" style="color: #059669;">Rp {{ number_format($portfolioGrossProfit, 0, ',', '.') }}</div>
            </div>
            <div class="kpi-item">
                <h5>Rata-rata Margin (%)</h5>
                <div class="val" style="color: #059669;">{{ $portfolioMarginPct }}%</div>
            </div>
        </div>

        <!-- Statement Table -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 30px;" class="text-center">No</th>
                    <th>Kode & Nama Proyek</th>
                    <th>Klien Korporasi</th>
                    <th class="text-right">Nilai Kontrak</th>
                    <th class="text-right">Beban Tenaga Kerja</th>
                    <th class="text-right">Beban Langsung</th>
                    <th class="text-right">Total Beban (HPP)</th>
                    <th class="text-right">Laba Kotor (IDR)</th>
                    <th class="text-center">Margin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $idx => $p)
                    @php
                        $labor = $p->approvedLaborCost();
                        $expense = $p->approvedDirectExpenses();
                        $cost = $labor + $expense;
                        $profit = (float)$p->contract_value - $cost;
                        $margin = (float)$p->contract_value > 0 ? round(($profit / (float)$p->contract_value) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td class="text-center" style="color: #64748b;">{{ $idx + 1 }}</td>
                        <td>
                            <strong>{{ $p->project_code }}</strong> - {{ $p->name }}
                        </td>
                        <td>{{ $p->client->company_name ?? '-' }}</td>
                        <td class="text-right font-mono font-bold">Rp {{ number_format($p->contract_value, 0, ',', '.') }}</td>
                        <td class="text-right font-mono">Rp {{ number_format($labor, 0, ',', '.') }}</td>
                        <td class="text-right font-mono">Rp {{ number_format($expense, 0, ',', '.') }}</td>
                        <td class="text-right font-mono font-bold">Rp {{ number_format($cost, 0, ',', '.') }}</td>
                        <td class="text-right font-mono font-bold" style="color: {{ $profit >= 0 ? '#059669' : '#e11d48' }};">
                            Rp {{ number_format($profit, 0, ',', '.') }}
                        </td>
                        <td class="text-center font-bold" style="color: {{ $margin >= 30 ? '#059669' : ($margin > 0 ? '#d97706' : '#e11d48') }};">
                            {{ $margin }}%
                        </td>
                    </tr>
                @endforeach
                <!-- Total Consolidated Row -->
                <tr style="background: #f8fafc; font-weight: 800;">
                    <td colspan="3" class="text-center" style="padding: 12px 10px;">TOTAL KONSOLIDASI PORTOFOLIO</td>
                    <td class="text-right font-mono">Rp {{ number_format($portfolioContractValue, 0, ',', '.') }}</td>
                    <td class="text-right font-mono">Rp {{ number_format($portfolioLaborCost, 0, ',', '.') }}</td>
                    <td class="text-right font-mono">Rp {{ number_format($portfolioDirectExpense, 0, ',', '.') }}</td>
                    <td class="text-right font-mono">Rp {{ number_format($portfolioTotalCost, 0, ',', '.') }}</td>
                    <td class="text-right font-mono" style="color: #059669;">Rp {{ number_format($portfolioGrossProfit, 0, ',', '.') }}</td>
                    <td class="text-center" style="color: #059669;">{{ $portfolioMarginPct }}%</td>
                </tr>
            </tbody>
        </table>

        <!-- Signatures Section -->
        <div class="signature-section">
            <div class="sig-block">
                <div style="font-size: 11px; color: #64748b; margin-bottom: 4px;">Dibuat & Diverifikasi Oleh,</div>
                <div style="font-weight: 600; font-size: 12px; color: #0f172a;">Finance & Tax Department</div>
                <div class="sig-space"></div>
                <div class="sig-name">Anisa Tri Putri, S.E., Ak.</div>
                <div class="sig-role">Corporate Finance & Tax Manager</div>
            </div>

            <div class="sig-block">
                <div style="font-size: 11px; color: #64748b; margin-bottom: 4px;">Disetujui Untuk Dewan Direksi,</div>
                <div style="font-weight: 600; font-size: 12px; color: #0f172a;">Executive Board of Directors</div>
                <div class="sig-space"></div>
                <div class="sig-name">Alexander Pratama, S.Kom., M.M.</div>
                <div class="sig-role">Managing Director / CEO</div>
            </div>
        </div>

    </div>

</body>
</html>

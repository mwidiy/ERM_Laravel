# NEXUS ERP Enterprise

Sistem ERP berbasis Laravel untuk pengelolaan portofolio proyek, klien, alokasi tim, pencatatan timesheet, penagihan termin faktur, dan analisis keuangan proyek.

## Fitur Utama

- Manajemen data klien (CRM)
- Manajemen proyek, milestone termin, dan tugas teknis (WBS)
- Pencatatan jam kerja konsultan (Timesheet) dan persetujuan
- Pencatatan pengeluaran langsung proyek (Expenses)
- Penerbitan faktur penagihan termin dan pencatatan pembayaran (AR)
- Laporan laba rugi proyek dan jadwal umur piutang
- Pembagian hak akses berbasis role (Super Admin, Admin, Project Manager, Finance, Consultant)

## Kebutuhan Sistem

- PHP 8.2 atau lebih baru
- Composer
- Node.js dan NPM
- MySQL / MariaDB (misal via XAMPP)

## Panduan Instalasi

1. Salin atau clone proyek ke direktori lokal.
2. Buka terminal di folder proyek.
3. Install dependensi PHP:
   composer install
4. Install dependensi JavaScript:
   npm install
5. Salin file konfigurasi environment:
   cp .env.example .env
6. Buka file .env lalu pastikan nama database sesuai (default: erp) dan sesuaikan username/password MySQL.
7. Generate key aplikasi:
   php artisan key:generate
8. Jalankan migrasi database beserta data awal:
   php artisan migrate:fresh --seed
9. Compile aset frontend:
   npm run build
10. Jalankan web server:
    php artisan serve

Aplikasi dapat dibuka di browser melalui alamat:
http://127.0.0.1:8000

## Akun Login Pengujian

Password untuk semua akun di bawah ini adalah: password

- Super Admin: admin@enterprise.co.id
- Admin: diana.admin@enterprise.co.id
- Project Manager: budi.pm@enterprise.co.id
- Finance: anisa.finance@enterprise.co.id
- Consultant: siti.tech@enterprise.co.id

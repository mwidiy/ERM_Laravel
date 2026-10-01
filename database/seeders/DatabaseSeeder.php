<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientContact;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectMilestone;
use App\Models\ProjectExpense;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;
use App\Models\ProjectTask;
use App\Models\Service;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with enterprise initial data.
     */
    public function run(): void
    {
        // 1. Seed Enterprise Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@enterprise.co.id'],
            [
                'name' => 'Alexander Pratama',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'role' => 'super_admin',
                'position' => 'Managing Director & Principal Consultant',
                'hourly_rate' => 350000,
                'status' => 'active',
            ]
        );

        $pm = User::firstOrCreate(
            ['email' => 'budi.pm@enterprise.co.id'],
            [
                'name' => 'Budi Santoso, PMP',
                'password' => Hash::make('password'),
                'phone' => '081298765432',
                'role' => 'project_manager',
                'position' => 'Senior Project Delivery Manager',
                'hourly_rate' => 250000,
                'status' => 'active',
            ]
        );

        $architect = User::firstOrCreate(
            ['email' => 'siti.tech@enterprise.co.id'],
            [
                'name' => 'Siti Nurhaliza, M.Kom',
                'password' => Hash::make('password'),
                'phone' => '081345678901',
                'role' => 'consultant',
                'position' => 'Lead Enterprise Cloud Architect',
                'hourly_rate' => 275000,
                'status' => 'active',
            ]
        );

        $dev = User::firstOrCreate(
            ['email' => 'denny.dev@enterprise.co.id'],
            [
                'name' => 'Denny Kurniawan',
                'password' => Hash::make('password'),
                'phone' => '081456789012',
                'role' => 'consultant',
                'position' => 'Senior Fullstack Software Engineer',
                'hourly_rate' => 175000,
                'status' => 'active',
            ]
        );

        $finance = User::firstOrCreate(
            ['email' => 'anisa.finance@enterprise.co.id'],
            [
                'name' => 'Anisa Tri Putri, S.E., Ak.',
                'password' => Hash::make('password'),
                'phone' => '081567890123',
                'role' => 'finance',
                'position' => 'Corporate Finance & Tax Manager',
                'hourly_rate' => 200000,
                'status' => 'active',
            ]
        );

        // 2. Seed Services Catalog
        $services = [
            [
                'service_code' => 'SVC-ARCH-01',
                'name' => 'Enterprise System & Cloud Architecture Consulting',
                'category' => 'Cloud & Infrastructure',
                'description' => 'Perancangan arsitektur enterprise berskala besar, microservices, dan tata kelola cloud multi-region.',
                'billing_type' => 'hourly',
                'default_rate' => 300000,
                'status' => 'active',
            ],
            [
                'service_code' => 'SVC-DEV-02',
                'name' => 'Custom Software & ERP Platform Development',
                'category' => 'Software Engineering',
                'description' => 'Pengembangan sistem informasi terintegrasi kustom (Web & Mobile) berstandar industri.',
                'billing_type' => 'fixed',
                'default_rate' => 200000,
                'status' => 'active',
            ],
            [
                'service_code' => 'SVC-SEC-03',
                'name' => 'IT Security Audit, Penetration Test & ISO 27001 Compliance',
                'category' => 'Cybersecurity',
                'description' => 'Audit keamanan sistem digital perusahaan dan sertifikasi kepatuhan regulasi finansial.',
                'billing_type' => 'fixed',
                'default_rate' => 350000,
                'status' => 'active',
            ],
            [
                'service_code' => 'SVC-UX-04',
                'name' => 'UX Research, Design System & Prototyping',
                'category' => 'Product Design',
                'description' => 'Riset pengalaman pengguna mendalam, pembuatan design token, dan clickable prototype high-fidelity.',
                'billing_type' => 'hourly',
                'default_rate' => 175000,
                'status' => 'active',
            ],
        ];

        foreach ($services as $svc) {
            Service::firstOrCreate(['service_code' => $svc['service_code']], $svc);
        }

        // 3. Seed Corporate Clients & Contacts
        $client1 = Client::firstOrCreate(
            ['client_code' => 'CLI-2026-001'],
            [
                'company_name' => 'Bank Mitra Usaha Mandiri, Tbk',
                'industry' => 'Banking & Financial Services',
                'email' => 'vendor.relations@bankmitra.co.id',
                'phone' => '+62 21 5566 7788',
                'website' => 'https://bankmitra.co.id',
                'address' => 'Menara Mandiri Lt. 24, Jl. Jend. Sudirman Kav. 54-55',
                'city' => 'Jakarta Selatan',
                'tax_id' => '01.234.567.8-012.000',
                'payment_terms_days' => 30,
                'credit_limit' => 2000000000,
                'status' => 'active',
            ]
        );

        ClientContact::firstOrCreate(
            ['client_id' => $client1->id, 'email' => 'hendra.gunawan@bankmitra.co.id'],
            [
                'name' => 'Hendra Gunawan',
                'position' => 'Head of Digital Banking Architecture',
                'phone' => '081122334455',
                'is_primary' => true,
            ]
        );

        $client2 = Client::firstOrCreate(
            ['client_code' => 'CLI-2026-002'],
            [
                'company_name' => 'PT Telko Nusantara Digital',
                'industry' => 'Telecommunications & Media',
                'email' => 'procurement@telkonusantara.id',
                'phone' => '+62 21 7890 1234',
                'website' => 'https://telkonusantara.id',
                'address' => 'Gedung Telko Cyber Park Blok C, TB Simatupang',
                'city' => 'Jakarta Selatan',
                'tax_id' => '02.345.678.9-023.000',
                'payment_terms_days' => 45,
                'credit_limit' => 1500000000,
                'status' => 'active',
            ]
        );

        ClientContact::firstOrCreate(
            ['client_id' => $client2->id, 'email' => 'rina.procurement@telkonusantara.id'],
            [
                'name' => 'Rina Wijayanti',
                'position' => 'Procurement & Vendor Specialist',
                'phone' => '081233445566',
                'is_primary' => true,
            ]
        );

        $client3 = Client::firstOrCreate(
            ['client_code' => 'CLI-2026-003'],
            [
                'company_name' => 'PT Global Logistik Distribusi',
                'industry' => 'Supply Chain & Logistics',
                'email' => 'it.dept@globallogistik.co.id',
                'phone' => '+62 31 8899 0011',
                'website' => 'https://globallogistik.co.id',
                'address' => 'Kawasan Industri Rungkut Megah No. 12',
                'city' => 'Surabaya',
                'tax_id' => '03.456.789.0-034.000',
                'payment_terms_days' => 14,
                'credit_limit' => 500000000,
                'status' => 'active',
            ]
        );

        ClientContact::firstOrCreate(
            ['client_id' => $client3->id, 'email' => 'eko.subagyo@globallogistik.co.id'],
            [
                'name' => 'Eko Subagyo',
                'position' => 'Chief Technology Officer (CTO)',
                'phone' => '081344556677',
                'is_primary' => true,
            ]
        );

        // 4. Seed Projects
        $project1 = Project::firstOrCreate(
            ['project_code' => 'PRJ-2026-001'],
            [
                'client_id' => $client1->id,
                'project_manager_id' => $pm->id,
                'name' => 'Core Banking Modernization & Microservices Gateway',
                'description' => 'Transformasi arsitektur perbankan terpadu, integrasi Open API BI-FAST, dan migrasi microservices berkeamanan tinggi.',
                'contract_type' => 'fixed_fee',
                'contract_value' => 850000000,
                'budget_cost' => 450000000,
                'start_date' => now()->subMonths(1)->format('Y-m-d'),
                'end_date' => now()->addMonths(5)->format('Y-m-d'),
                'progress_percentage' => 45,
                'status' => 'in_progress',
                'priority' => 'urgent',
            ]
        );

        // Milestones Project 1
        $p1Milestones = [
            [
                'title' => 'Termin 1: Project Charter & Architecture Blueprint',
                'description' => 'Penyusunan blueprint arsitektur, sign-off BRD & SRS serta dokumen keamanan sistem.',
                'due_date' => now()->subWeeks(2)->format('Y-m-d'),
                'completion_percentage' => 100,
                'billing_percentage' => 30,
                'billing_amount' => 255000000,
                'status' => 'invoiced',
            ],
            [
                'title' => 'Termin 2: Microservices Core Engine & API Gateway MVP',
                'description' => 'Implementasi routing engine, rate limiter, autentikasi OAuth2/mTLS, dan konektor core banking.',
                'due_date' => now()->addWeeks(3)->format('Y-m-d'),
                'completion_percentage' => 70,
                'billing_percentage' => 40,
                'billing_amount' => 340000000,
                'status' => 'in_progress',
            ],
            [
                'title' => 'Termin 3: UAT, Penetration Testing & Go-Live Final Sign-off',
                'description' => 'Pelaksanaan User Acceptance Test, stress testing 50.000 TPS, perbaikan temuan audit, dan serah terima operasional.',
                'due_date' => now()->addMonths(5)->format('Y-m-d'),
                'completion_percentage' => 0,
                'billing_percentage' => 30,
                'billing_amount' => 255000000,
                'status' => 'pending',
            ],
        ];

        foreach ($p1Milestones as $m) {
            ProjectMilestone::firstOrCreate(
                ['project_id' => $project1->id, 'title' => $m['title']],
                $m
            );
        }

        // Members Project 1
        ProjectMember::firstOrCreate(
            ['project_id' => $project1->id, 'user_id' => $pm->id],
            ['project_role' => 'Project Manager', 'hourly_rate' => 250000, 'assigned_date' => now()->subMonths(1)->format('Y-m-d')]
        );
        ProjectMember::firstOrCreate(
            ['project_id' => $project1->id, 'user_id' => $architect->id],
            ['project_role' => 'Lead Enterprise Architect', 'hourly_rate' => 275000, 'assigned_date' => now()->subMonths(1)->format('Y-m-d')]
        );
        ProjectMember::firstOrCreate(
            ['project_id' => $project1->id, 'user_id' => $dev->id],
            ['project_role' => 'Senior Backend Engineer', 'hourly_rate' => 175000, 'assigned_date' => now()->subMonths(1)->format('Y-m-d')]
        );

        // Tasks for Project 1
        $t1 = ProjectTask::firstOrCreate(
            ['project_id' => $project1->id, 'title' => 'Desain Arsitektur Microservices & Diagram Topologi Cloud'],
            [
                'assigned_to' => $architect->id,
                'description' => 'Penyusunan blueprint arsitektur, pemilihan service mesh, dan protokol mTLS inter-service.',
                'estimated_hours' => 40,
                'actual_hours' => 38,
                'priority' => 'high',
                'status' => 'done',
                'due_date' => now()->subWeeks(2)->format('Y-m-d'),
            ]
        );

        $t2 = ProjectTask::firstOrCreate(
            ['project_id' => $project1->id, 'title' => 'Setup CI/CD Pipeline & Kubernetes Cluster Multi-Region'],
            [
                'assigned_to' => $dev->id,
                'description' => 'Konfigurasi GitOps ArgoCD, helm charts, dan automated testing pipeline.',
                'estimated_hours' => 30,
                'actual_hours' => 28,
                'priority' => 'medium',
                'status' => 'done',
                'due_date' => now()->subWeeks(1)->format('Y-m-d'),
            ]
        );

        $t3 = ProjectTask::firstOrCreate(
            ['project_id' => $project1->id, 'title' => 'Implementasi OAuth2 Gateway & Rate Limiting Connector'],
            [
                'assigned_to' => $dev->id,
                'description' => 'Implementasi JWT token verification, sliding window rate limiter, dan audit log.',
                'estimated_hours' => 60,
                'actual_hours' => 24,
                'priority' => 'urgent',
                'status' => 'in_progress',
                'due_date' => now()->addWeeks(2)->format('Y-m-d'),
            ]
        );

        $t4 = ProjectTask::firstOrCreate(
            ['project_id' => $project1->id, 'title' => 'Integrasi API BI-FAST Sandbox & Message Signature Validations'],
            [
                'assigned_to' => $architect->id,
                'description' => 'Koneksi ke sandbox Bank Indonesia, hashing SHA-256, dan validasi transaksi realtime.',
                'estimated_hours' => 50,
                'actual_hours' => 18,
                'priority' => 'high',
                'status' => 'in_progress',
                'due_date' => now()->addWeeks(3)->format('Y-m-d'),
            ]
        );

        $t5 = ProjectTask::firstOrCreate(
            ['project_id' => $project1->id, 'title' => 'Stress Test 50.000 TPS & Failover High-Availability Simulation'],
            [
                'assigned_to' => $dev->id,
                'description' => 'Uji beban ekstrim menggunakan k6/JMeter dan pengujian skenario node failure.',
                'estimated_hours' => 40,
                'actual_hours' => 0,
                'priority' => 'urgent',
                'status' => 'todo',
                'due_date' => now()->addMonths(2)->format('Y-m-d'),
            ]
        );

        // Timesheets for Project 1
        Timesheet::firstOrCreate(
            ['project_id' => $project1->id, 'user_id' => $architect->id, 'entry_date' => now()->subWeeks(2)->format('Y-m-d')],
            [
                'task_id' => $t1->id,
                'hours' => 38,
                'description' => 'Penyelesaian dokumen SRS Arsitektur dan presentasi ke Direksi Bank Mitra.',
                'is_billable' => true,
                'hourly_rate' => 275000,
                'total_cost' => 38 * 275000, // 10.450.000
                'status' => 'approved',
                'approved_by' => $pm->id,
                'approved_at' => now()->subWeeks(2)->addDays(1),
            ]
        );

        Timesheet::firstOrCreate(
            ['project_id' => $project1->id, 'user_id' => $dev->id, 'entry_date' => now()->subWeeks(1)->format('Y-m-d')],
            [
                'task_id' => $t2->id,
                'hours' => 28,
                'description' => 'Setup cluster Kubernetes staging dan deployment testing pod.',
                'is_billable' => true,
                'hourly_rate' => 175000,
                'total_cost' => 28 * 175000, // 4.900.000
                'status' => 'approved',
                'approved_by' => $pm->id,
                'approved_at' => now()->subWeeks(1)->addDays(1),
            ]
        );

        Timesheet::firstOrCreate(
            ['project_id' => $project1->id, 'user_id' => $dev->id, 'entry_date' => now()->subDays(2)->format('Y-m-d')],
            [
                'task_id' => $t3->id,
                'hours' => 24,
                'description' => 'Coding modul token validation dan integrasi Redis cluster untuk rate limiter.',
                'is_billable' => true,
                'hourly_rate' => 175000,
                'total_cost' => 24 * 175000, // 4.200.000
                'status' => 'submitted', // Pending review
            ]
        );

        Timesheet::firstOrCreate(
            ['project_id' => $project1->id, 'user_id' => $architect->id, 'entry_date' => now()->subDays(1)->format('Y-m-d')],
            [
                'task_id' => $t4->id,
                'hours' => 18,
                'description' => 'Pengujian end-to-end payload message signature dengan API Gateway BI-FAST.',
                'is_billable' => true,
                'hourly_rate' => 275000,
                'total_cost' => 18 * 275000, // 4.950.000
                'status' => 'submitted', // Pending review
            ]
        );

        // Project 2
        $project2 = Project::firstOrCreate(
            ['project_code' => 'PRJ-2026-002'],
            [
                'client_id' => $client2->id,
                'project_manager_id' => $pm->id,
                'name' => 'Corporate Customer Self-Care Portal & Mobile App',
                'description' => 'Pembangunan aplikasi self-care pelanggan korporasi berbasis web responsif dan mobile app (Flutter/iOS/Android).',
                'contract_type' => 'fixed_fee',
                'contract_value' => 620000000,
                'budget_cost' => 310000000,
                'start_date' => now()->subWeeks(2)->format('Y-m-d'),
                'end_date' => now()->addMonths(4)->format('Y-m-d'),
                'progress_percentage' => 25,
                'status' => 'in_progress',
                'priority' => 'high',
            ]
        );

        ProjectMilestone::firstOrCreate(
            ['project_id' => $project2->id, 'title' => 'Termin 1: DP & Design System Sign-off'],
            [
                'description' => 'Pembayaran Uang Muka 30% dan persetujuan clickable prototype Figma.',
                'due_date' => now()->subDays(5)->format('Y-m-d'),
                'completion_percentage' => 100,
                'billing_percentage' => 30,
                'billing_amount' => 186000000,
                'status' => 'completed',
            ]
        );

        ProjectMilestone::firstOrCreate(
            ['project_id' => $project2->id, 'title' => 'Termin 2: Frontend & Backend API Integration'],
            [
                'description' => 'Penyelesaian integrasi API billing telekomunikasi dan dashboard analitik pemakaian paket.',
                'due_date' => now()->addMonths(2)->format('Y-m-d'),
                'completion_percentage' => 20,
                'billing_percentage' => 40,
                'billing_amount' => 248000000,
                'status' => 'in_progress',
            ]
        );

        // Project 3
        $project3 = Project::firstOrCreate(
            ['project_code' => 'PRJ-2026-003'],
            [
                'client_id' => $client3->id,
                'project_manager_id' => $admin->id,
                'name' => 'WMS & Fleet Tracking Optimization System',
                'description' => 'Sistem pemantauan armada logistik real-time IoT dan manajemen pergudangan multi-cabang.',
                'contract_type' => 'time_and_material',
                'contract_value' => 380000000,
                'budget_cost' => 190000000,
                'start_date' => now()->addWeeks(1)->format('Y-m-d'),
                'end_date' => now()->addMonths(6)->format('Y-m-d'),
                'progress_percentage' => 0,
                'status' => 'planning',
                'priority' => 'medium',
            ]
        );

        // ==========================================
        // 6. PHASE 3: SEED PROJECT DIRECT EXPENSES
        // ==========================================
        ProjectExpense::firstOrCreate(
            ['expense_code' => 'EXP-202609-0001'],
            [
                'project_id' => $project1->id,
                'user_id' => $architect->id,
                'category' => 'transport_travel',
                'title' => 'Tiket Pesawat & Akomodasi Deployment DC Mandiri Surabaya',
                'description' => 'Perjalanan dinas 3 hari technical architect untuk setup private cloud gateway di Disaster Recovery Center.',
                'expense_date' => now()->subWeeks(2)->format('Y-m-d'),
                'amount' => 4850000,
                'is_billable_to_client' => true,
                'status' => 'approved',
                'approved_by' => $pm->id,
                'approved_at' => now()->subWeeks(2)->addDay(),
            ]
        );

        ProjectExpense::firstOrCreate(
            ['expense_code' => 'EXP-202609-0002'],
            [
                'project_id' => $project1->id,
                'user_id' => $dev->id,
                'category' => 'software_licenses',
                'title' => 'Langganan Dedicated AWS Sandbox Cluster Q4',
                'description' => 'Sewa infrastruktur AWS EKS untuk staging performance testing & mock banking API.',
                'expense_date' => now()->subDays(10)->format('Y-m-d'),
                'amount' => 8500000,
                'is_billable_to_client' => false,
                'status' => 'approved',
                'approved_by' => $pm->id,
                'approved_at' => now()->subDays(9),
            ]
        );

        ProjectExpense::firstOrCreate(
            ['expense_code' => 'EXP-202610-0003'],
            [
                'project_id' => $project2->id,
                'user_id' => $pm->id,
                'category' => 'meals_hospitality',
                'title' => 'Konsumsi & Jamuan Kickoff Meeting Bersama Manajemen Telco',
                'description' => 'Makan siang koordinasi dengan Vice President Digital & Enterprise Architecture Telco.',
                'expense_date' => now()->subDays(3)->format('Y-m-d'),
                'amount' => 1750000,
                'is_billable_to_client' => false,
                'status' => 'submitted',
            ]
        );

        ProjectExpense::firstOrCreate(
            ['expense_code' => 'EXP-202610-0004'],
            [
                'project_id' => $project2->id,
                'user_id' => $dev->id,
                'category' => 'software_licenses',
                'title' => 'Lisensi Postman Enterprise & API Stress Test Suite',
                'description' => 'Tool automated testing kontrak integrasi API billing telekomunikasi.',
                'expense_date' => now()->subDays(1)->format('Y-m-d'),
                'amount' => 3200000,
                'is_billable_to_client' => true,
                'status' => 'approved',
                'approved_by' => $pm->id,
                'approved_at' => now(),
            ]
        );

        // ==========================================
        // 7. PHASE 3: SEED INVOICES & PAYMENTS (AR)
        // ==========================================
        $m1Project1 = ProjectMilestone::where('project_id', $project1->id)->first();
        $m2Project1 = ProjectMilestone::where('project_id', $project1->id)->skip(1)->first();
        $m1Project2 = ProjectMilestone::where('project_id', $project2->id)->first();

        // Invoice 1: Termin 1 Mandiri (LUNAS / PAID)
        $inv1 = Invoice::firstOrCreate(
            ['invoice_number' => 'INV/2026/09/0001'],
            [
                'project_id' => $project1->id,
                'client_id' => $client1->id,
                'milestone_id' => $m1Project1?->id,
                'issue_date' => now()->subDays(25)->format('Y-m-d'),
                'due_date' => now()->addDays(5)->format('Y-m-d'),
                'subtotal' => 255000000,
                'tax_rate' => 11.00,
                'tax_amount' => 28050000,
                'withholding_tax_rate' => 2.00,
                'withholding_tax_amount' => 5100000,
                'discount_amount' => 0,
                'total_amount' => 277950000,
                'paid_amount' => 277950000,
                'remaining_balance' => 0,
                'status' => 'paid',
                'payment_terms' => 'Net 30 Hari (Termin 1 DP 30%)',
                'bank_details' => "Bank Mandiri Rekening: 137-00-1928374-1\na.n. PT Enterprise Solusi Indonesia\nCabang Jakarta Sudirman",
                'notes' => 'Pembayaran uang muka Termin 1 telah lunas diterima via transfer RTGS.',
                'created_by' => $finance->id,
            ]
        );

        InvoiceItem::firstOrCreate(
            ['invoice_id' => $inv1->id, 'item_description' => 'Termin 1: Uang Muka (DP) & System Architecture Design Sign-off (30%)'],
            [
                'quantity' => 1,
                'unit' => 'Termin',
                'unit_price' => 255000000,
                'total_price' => 255000000,
            ]
        );

        InvoicePayment::firstOrCreate(
            ['payment_number' => 'PAY-202609-0001'],
            [
                'invoice_id' => $inv1->id,
                'payment_date' => now()->subDays(20)->format('Y-m-d'),
                'amount' => 277950000,
                'payment_method' => 'bank_transfer',
                'reference_number' => 'RTGS-MDR-88991201',
                'notes' => 'Pelunasan termin 1 via RTGS Mandiri Corporate.',
                'recorded_by' => $finance->id,
            ]
        );

        // Invoice 2: Termin 1 Telkom (PARTIALLY PAID)
        $inv2 = Invoice::firstOrCreate(
            ['invoice_number' => 'INV/2026/09/0002'],
            [
                'project_id' => $project2->id,
                'client_id' => $client2->id,
                'milestone_id' => $m1Project2?->id,
                'issue_date' => now()->subDays(15)->format('Y-m-d'),
                'due_date' => now()->addDays(15)->format('Y-m-d'),
                'subtotal' => 186000000,
                'tax_rate' => 11.00,
                'tax_amount' => 20460000,
                'withholding_tax_rate' => 2.00,
                'withholding_tax_amount' => 3720000,
                'discount_amount' => 0,
                'total_amount' => 202740000,
                'paid_amount' => 100000000,
                'remaining_balance' => 102740000,
                'status' => 'partially_paid',
                'payment_terms' => 'Net 30 Hari',
                'bank_details' => "Bank Mandiri Rekening: 137-00-1928374-1\na.n. PT Enterprise Solusi Indonesia\nCabang Jakarta Sudirman",
                'notes' => 'Pembayaran tahap 1 telah ditransfer sebesar Rp 100jt, sisa saldo akan ditransfer minggu depan.',
                'created_by' => $finance->id,
            ]
        );

        InvoiceItem::firstOrCreate(
            ['invoice_id' => $inv2->id, 'item_description' => 'Termin 1: Uang Muka 30% dan Prototype Design Sign-off'],
            [
                'quantity' => 1,
                'unit' => 'Paket',
                'unit_price' => 186000000,
                'total_price' => 186000000,
            ]
        );

        InvoicePayment::firstOrCreate(
            ['payment_number' => 'PAY-202609-0002'],
            [
                'invoice_id' => $inv2->id,
                'payment_date' => now()->subDays(5)->format('Y-m-d'),
                'amount' => 100000000,
                'payment_method' => 'bank_transfer',
                'reference_number' => 'TRF-TEL-44332211',
                'notes' => 'Pembayaran parsial Termin 1 Telco.',
                'recorded_by' => $finance->id,
            ]
        );

        // Invoice 3: Termin 2 Mandiri (OVERDUE ALERT)
        $inv3 = Invoice::firstOrCreate(
            ['invoice_number' => 'INV/2026/09/0003'],
            [
                'project_id' => $project1->id,
                'client_id' => $client1->id,
                'milestone_id' => $m2Project1?->id,
                'issue_date' => now()->subDays(40)->format('Y-m-d'),
                'due_date' => now()->subDays(10)->format('Y-m-d'), // Jatuh tempo 10 hari lalu
                'subtotal' => 340000000,
                'tax_rate' => 11.00,
                'tax_amount' => 37400000,
                'withholding_tax_rate' => 2.00,
                'withholding_tax_amount' => 6800000,
                'discount_amount' => 0,
                'total_amount' => 370600000,
                'paid_amount' => 0,
                'remaining_balance' => 370600000,
                'status' => 'overdue',
                'payment_terms' => 'Net 30 Hari',
                'bank_details' => "Bank Mandiri Rekening: 137-00-1928374-1\na.n. PT Enterprise Solusi Indonesia\nCabang Jakarta Sudirman",
                'notes' => 'Surat peringatan penagihan pertama telah dikirimkan ke finance procurement.',
                'created_by' => $finance->id,
            ]
        );

        InvoiceItem::firstOrCreate(
            ['invoice_id' => $inv3->id, 'item_description' => 'Termin 2: Microservices Engine & Database Migration (40%)'],
            [
                'quantity' => 1,
                'unit' => 'Termin',
                'unit_price' => 340000000,
                'total_price' => 340000000,
            ]
        );

        // Invoice 4: Jasa Kickoff WMS Logistik (SENT / UNPAID)
        $inv4 = Invoice::firstOrCreate(
            ['invoice_number' => 'INV/2026/10/0004'],
            [
                'project_id' => $project3->id,
                'client_id' => $client3->id,
                'milestone_id' => null,
                'issue_date' => now()->subDays(2)->format('Y-m-d'),
                'due_date' => now()->addDays(28)->format('Y-m-d'),
                'subtotal' => 50000000,
                'tax_rate' => 11.00,
                'tax_amount' => 5500000,
                'withholding_tax_rate' => 2.00,
                'withholding_tax_amount' => 1000000,
                'discount_amount' => 0,
                'total_amount' => 54500000,
                'paid_amount' => 0,
                'remaining_balance' => 54500000,
                'status' => 'sent',
                'payment_terms' => 'Net 30 Hari',
                'bank_details' => "Bank Mandiri Rekening: 137-00-1928374-1\na.n. PT Enterprise Solusi Indonesia\nCabang Jakarta Sudirman",
                'notes' => 'Faktur kickoff awal persiapan implementasi WMS.',
                'created_by' => $admin->id,
            ]
        );

        InvoiceItem::firstOrCreate(
            ['invoice_id' => $inv4->id, 'item_description' => 'Jasa Konsultasi Kickoff & Blueprint Arsitektur IoT WMS'],
            [
                'quantity' => 1,
                'unit' => 'Paket',
                'unit_price' => 50000000,
                'total_price' => 50000000,
            ]
        );
    }
}


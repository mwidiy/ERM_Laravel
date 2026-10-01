<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\ProfitabilityController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Authenticated ERP Enterprise Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    // User Session Termination
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Executive Dashboard (Accessible to all authenticated corporate roles)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Master Klien / CRM (Super Admin, Admin, Project Manager)
    Route::middleware('role:super_admin,admin,project_manager')->group(function () {
        Route::resource('clients', ClientController::class);
    });

    // Projects Management (WBS, Milestones & Allocation)
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::middleware('role:super_admin,admin,project_manager')->group(function () {
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::patch('/projects/{project}', [ProjectController::class, 'update']);
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
        Route::post('/projects/{project}/milestones', [ProjectController::class, 'storeMilestone'])->name('projects.milestones.store');
        Route::post('/projects/{project}/members', [ProjectController::class, 'assignMember'])->name('projects.members.assign');
        Route::delete('/projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])->name('projects.members.remove');
    });
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

    // Service Catalog & Master Billing Rates (Super Admin, Admin, Finance)
    Route::middleware('role:super_admin,admin,finance')->group(function () {
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    });

    // Team & Consultant Allocation (Super Admin, Admin, Project Manager)
    Route::middleware('role:super_admin,admin,project_manager')->group(function () {
        Route::get('/team', [TeamController::class, 'index'])->name('team.index');
        Route::post('/team', [TeamController::class, 'store'])->name('team.store');
    });

    // Phase 2: Tasks & Kanban WBS (All authenticated roles)
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Phase 2: Timesheet & Resource Tracking
    Route::get('/timesheets', [TimesheetController::class, 'index'])->name('timesheets.index');
    Route::post('/timesheets', [TimesheetController::class, 'store'])->name('timesheets.store');
    Route::delete('/timesheets/{timesheet}', [TimesheetController::class, 'destroy'])->name('timesheets.destroy');
    // Timesheet Approval Actions (Super Admin, Admin, Project Manager)
    Route::middleware('role:super_admin,admin,project_manager')->group(function () {
        Route::patch('/timesheets/{timesheet}/approve', [TimesheetController::class, 'approve'])->name('timesheets.approve');
        Route::patch('/timesheets/{timesheet}/reject', [TimesheetController::class, 'reject'])->name('timesheets.reject');
    });

    // Phase 3: Project Direct Expenses & Costing
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    // Expense Approval Actions (Super Admin, Admin, Project Manager, Finance)
    Route::middleware('role:super_admin,admin,project_manager,finance')->group(function () {
        Route::patch('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
        Route::patch('/expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
    });

    // Phase 3: Invoicing Engine & AR Tracking (Super Admin, Admin, Finance, Project Manager)
    Route::middleware('role:super_admin,admin,finance,project_manager')->group(function () {
        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
        Route::patch('/invoices/{invoice}/send', [InvoiceController::class, 'markAsSent'])->name('invoices.send');
        Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');

        // Invoice Payments & AR Collections
        Route::post('/invoices/{invoice}/payments', [InvoicePaymentController::class, 'store'])->name('invoices.payments.store');
        Route::delete('/payments/{payment}', [InvoicePaymentController::class, 'destroy'])->name('payments.destroy');
    });

    // Phase 4: Project Profitability & Financial Statements (Confidential: Super Admin, Admin, Finance)
    Route::middleware('role:super_admin,admin,finance')->group(function () {
        Route::get('/profitability', [ProfitabilityController::class, 'index'])->name('profitability.index');
        Route::get('/profitability/ar-aging', [ProfitabilityController::class, 'arAging'])->name('profitability.arAging');
        Route::get('/profitability/print', [ProfitabilityController::class, 'printReport'])->name('profitability.print');
        Route::get('/profitability/{project}', [ProfitabilityController::class, 'show'])->name('profitability.show');
    });

    // Legacy routes preserved
    Route::get('/tambah-barang', [ProductController::class, 'create']);
    Route::post('/simpan-barang', [ProductController::class, 'store']);
});
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_code')->unique(); // e.g. EXP-2026-001
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('category', [
                'transport_travel',
                'software_licenses',
                'equipment_materials',
                'meals_hospitality',
                'subcontractor',
                'operational_other'
            ])->default('operational_other');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('expense_date');
            $table->decimal('amount', 15, 2);
            $table->string('receipt_path')->nullable(); // Upload nota/bukti
            $table->boolean('is_billable_to_client')->default(false);
            $table->enum('status', ['submitted', 'approved', 'rejected', 'reimbursed'])->default('submitted');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_expenses');
    }
};

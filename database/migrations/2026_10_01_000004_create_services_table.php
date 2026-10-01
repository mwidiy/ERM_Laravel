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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_code')->unique(); // e.g. SVC-DEV-01
            $table->string('name');
            $table->string('category')->default('General Consulting'); // e.g. Software Engineering, UI/UX, Infrastructure
            $table->text('description')->nullable();
            $table->enum('billing_type', ['hourly', 'fixed', 'monthly_retainer'])->default('hourly');
            $table->decimal('default_rate', 15, 2)->default(0); // Standard billing rate
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

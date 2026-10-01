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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->enum('role', ['admin', 'project_manager', 'consultant', 'finance'])->default('consultant')->after('password');
            $table->string('position')->nullable()->after('role'); // e.g. Senior Solution Architect
            $table->decimal('hourly_rate', 12, 2)->default(0)->after('position'); // internal cost rate per jam
            $table->enum('status', ['active', 'inactive'])->default('active')->after('hourly_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'role', 'position', 'hourly_rate', 'status']);
        });
    }
};

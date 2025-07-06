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
        Schema::table('employees', function (Blueprint $table) {
            // Add the three_months_increase_date column if it doesn't exist
            if (!Schema::hasColumn('employees', 'three_months_increase_date')) {
                $table->date('three_months_increase_date')->nullable()->after('initial_salary');
            }
            
            // Add the has_first_increase column
            $table->boolean('has_first_increase')->default(false)->after('three_months_increase_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['has_first_increase', 'three_months_increase_date']);
        });
    }
};

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
            // Date d'augmentation après 3 ans
            $table->date('three_years_increase_date')->nullable()->after('salary_increase_status');
            // Montant de l'augmentation après 3 ans
            $table->decimal('three_years_increase_amount', 10, 2)->nullable()->after('three_years_increase_date');
            // Statut de l'augmentation après 3 ans
            $table->enum('three_years_increase_status', ['pending', 'applied'])->default('pending')->after('three_years_increase_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['three_years_increase_date', 'three_years_increase_amount', 'three_years_increase_status']);
        });
    }
};

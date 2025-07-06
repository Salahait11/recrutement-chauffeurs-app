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
            // Salaire initial (avant augmentation)
            $table->decimal('initial_salary', 10, 2)->nullable()->after('salary');
            // Date d'augmentation automatique (3 mois après embauche)
            $table->date('salary_increase_date')->nullable()->after('initial_salary');
            // Montant de l'augmentation
            $table->decimal('salary_increase_amount', 10, 2)->nullable()->after('salary_increase_date');
            // Statut de l'augmentation
            $table->enum('salary_increase_status', ['pending', 'applied'])->default('pending')->after('salary_increase_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['initial_salary', 'salary_increase_date', 'salary_increase_amount', 'salary_increase_status']);
        });
    }
};

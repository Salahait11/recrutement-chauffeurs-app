<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mettre à jour les statuts existants
        DB::table('leave_requests')
            ->where('status', 'pending')
            ->update(['status' => 'en_attente']);

        DB::table('leave_requests')
            ->where('status', 'approved')
            ->update(['status' => 'approuve']);

        DB::table('leave_requests')
            ->where('status', 'rejected')
            ->update(['status' => 'refuse']);

        DB::table('leave_requests')
            ->where('status', 'canceled')
            ->update(['status' => 'annule']);

        // Modifier la contrainte ENUM
        DB::statement("ALTER TABLE leave_requests MODIFY COLUMN status ENUM('en_attente', 'approuve', 'refuse', 'annule') DEFAULT 'en_attente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer les statuts en anglais
        DB::table('leave_requests')
            ->where('status', 'en_attente')
            ->update(['status' => 'pending']);

        DB::table('leave_requests')
            ->where('status', 'approuve')
            ->update(['status' => 'approved']);

        DB::table('leave_requests')
            ->where('status', 'refuse')
            ->update(['status' => 'rejected']);

        DB::table('leave_requests')
            ->where('status', 'annule')
            ->update(['status' => 'canceled']);

        // Restaurer la contrainte ENUM originale
        DB::statement("ALTER TABLE leave_requests MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'canceled') DEFAULT 'pending'");
    }
};

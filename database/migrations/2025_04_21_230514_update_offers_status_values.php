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
        DB::table('offers')
            ->where('status', 'draft')
            ->update(['status' => 'brouillon']);

        DB::table('offers')
            ->where('status', 'sent')
            ->update(['status' => 'envoyee']);

        DB::table('offers')
            ->where('status', 'accepted')
            ->update(['status' => 'acceptee']);

        DB::table('offers')
            ->where('status', 'rejected')
            ->update(['status' => 'refusee']);

        DB::table('offers')
            ->where('status', 'expired')
            ->update(['status' => 'expiree']);

        DB::table('offers')
            ->where('status', 'withdrawn')
            ->update(['status' => 'retiree']);

        // Modifier la contrainte ENUM
        DB::statement("ALTER TABLE offers MODIFY COLUMN status ENUM('brouillon', 'envoyee', 'acceptee', 'refusee', 'expiree', 'retiree') DEFAULT 'brouillon'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer les statuts en anglais
        DB::table('offers')
            ->where('status', 'brouillon')
            ->update(['status' => 'draft']);

        DB::table('offers')
            ->where('status', 'envoyee')
            ->update(['status' => 'sent']);

        DB::table('offers')
            ->where('status', 'acceptee')
            ->update(['status' => 'accepted']);

        DB::table('offers')
            ->where('status', 'refusee')
            ->update(['status' => 'rejected']);

        DB::table('offers')
            ->where('status', 'expiree')
            ->update(['status' => 'expired']);

        DB::table('offers')
            ->where('status', 'retiree')
            ->update(['status' => 'withdrawn']);

        // Restaurer la contrainte ENUM originale
        DB::statement("ALTER TABLE offers MODIFY COLUMN status ENUM('draft', 'sent', 'accepted', 'rejected', 'expired', 'withdrawn') DEFAULT 'draft'");
    }
};

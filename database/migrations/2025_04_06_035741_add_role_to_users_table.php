<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Ajouter la colonne après 'password' ou 'remember_token'
            $table->string('role')->default('employee')->after('remember_token');
            // On pourrait utiliser enum, mais string est plus flexible au début
            // $table->enum('role', ['admin', 'recruiter', 'manager', 'employee'])->default('employee')->after('remember_token');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
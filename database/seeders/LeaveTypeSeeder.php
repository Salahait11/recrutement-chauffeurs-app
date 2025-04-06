<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder {
    public function run(): void {
        LeaveType::query()->delete();
        LeaveType::insert([
            ['name' => 'Congé Payé Annuel', 'requires_approval' => true, 'affects_balance' => true, 'is_active' => true, 'color_code' => '#2ECC71', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Congé Maladie (Justifié)', 'requires_approval' => true, 'affects_balance' => false, 'is_active' => true, 'color_code' => '#F1C40F', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Congé Sans Solde', 'requires_approval' => true, 'affects_balance' => false, 'is_active' => true, 'color_code' => '#95A5A6', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'RTT', 'requires_approval' => true, 'affects_balance' => true, 'is_active' => true, 'color_code' => '#3498DB', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
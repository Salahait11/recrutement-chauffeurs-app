<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder {
    public function run(): void {
        $leaveTypes = [
            [
                'name' => 'Congé Payé Annuel',
                'requires_approval' => true,
                'affects_balance' => true,
                'is_active' => true,
                'color_code' => '#2ECC71'
            ],
            [
                'name' => 'Congé Maladie',
                'requires_approval' => true,
                'affects_balance' => false,
                'is_active' => true,
                'color_code' => '#F1C40F'
            ],
            [
                'name' => 'Congé Sans Solde',
                'requires_approval' => true,
                'affects_balance' => false,
                'is_active' => true,
                'color_code' => '#95A5A6'
            ],
            [
                'name' => 'RTT',
                'requires_approval' => true,
                'affects_balance' => true,
                'is_active' => true,
                'color_code' => '#3498DB'
            ]
        ];

        foreach ($leaveTypes as $leaveType) {
            LeaveType::updateOrCreate(
                ['name' => $leaveType['name']],
                $leaveType
            );
        }

        $this->command->info('Types de congés créés/mis à jour.');
    }
}
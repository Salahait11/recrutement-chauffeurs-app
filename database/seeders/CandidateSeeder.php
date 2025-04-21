php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Candidate;
use Faker\Factory as Faker;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Candidate::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'birth_date' => $faker->date,
                'driving_license_number' => $faker->bothify('?#?#?#?#?#?#?#?'),
                'driving_license_expiry' => $faker->dateTimeBetween('+1 year', '+5 years'),
                'years_of_experience' => $faker->numberBetween(0, 20),
                'status' => $faker->randomElement(Candidate::$statuses),
                'notes' => $faker->optional()->text,
            ]);
        }
    }
}
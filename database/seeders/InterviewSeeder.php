<?php

namespace Database\Seeders;

use App\Models\Interview;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InterviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $candidateIds = Candidate::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        for ($i = 0; $i < 20; $i++) {
            $candidateId = $faker->randomElement($candidateIds);
            $interviewDate = $faker->dateTimeBetween('-1 year', '+1 year');

            Interview::updateOrCreate(
                ['candidate_id' => $candidateId, 'interview_date' => $interviewDate],
                [
                    'scheduler_id' => $faker->randomElement($userIds),
                    'interviewer_id' => $faker->randomElement($userIds),
                    'type' => $faker->randomElement(['initial', 'technique', 'final']),
                    'notes' => $faker->optional()->text,
                    'status' => $faker->randomElement(['planifié', 'terminé', 'annulé']),
                    'result' => $faker->optional()->text,
                    'feedback' => $faker->optional()->text,
                ]
            );
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Patient;
use Database\Factories\SubmissionFactory;
use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $randomPatient = Patient::inRandomOrder()->first();

        SubmissionFactory::new()->count(40)->create([
            'patient_id' => $randomPatient->id,
        ]);
    }
}

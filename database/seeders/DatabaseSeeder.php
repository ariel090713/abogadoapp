<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core Data (Required)
        $this->call([
            AdminSeeder::class,
            SpecializationSeeder::class,
            AISettingsSeeder::class,
            ContentCategorySeeder::class,
            LawyerSeeder::class,
            ConsultationSeeder::class,
            DocumentTemplateSeeder::class,
            ContentSeeder::class,
        ]);

        // Demo/Test Data (Optional - comment out for production)
        $this->call([
            AdminDashboardDataSeeder::class,  // 50+ clients, 200+ consultations, transactions
            OngoingConsultationsSeeder::class, // Ongoing chat & video consultations
        ]);
    }
}

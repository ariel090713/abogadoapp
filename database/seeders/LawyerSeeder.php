<?php

namespace Database\Seeders;

use App\Models\LawyerProfile;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LawyerSeeder extends Seeder
{
    public function run(): void
    {
        // Create test client
        $client = User::create([
            'name' => 'Test Client',
            'email' => 'client@example.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'phone' => '09123456789',
            'location' => 'Manila',
            'email_verified_at' => now(),
            'onboarding_completed_at' => now(),
        ]);
        
        $allSpecializations = Specialization::all();
        $parentSpecializations = Specialization::where('is_parent', true)->get();
        $locations = ['Manila', 'Quezon City', 'Makati', 'Cebu City', 'Davao City', 'Pasig', 'Taguig', 'Mandaluyong'];

        // Create 20 sample lawyers
        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name' => fake()->name(),
                'email' => 'lawyer' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'lawyer',
                'phone' => '09' . fake()->numerify('#########'),
                'location' => fake()->randomElement($locations),
                'email_verified_at' => now(),
                'onboarding_completed_at' => now(),
            ]);

            $lawyer = LawyerProfile::create([
                'user_id' => $user->id,
                'ibp_number' => fake()->unique()->numerify('IBP-####-####'),
                'bio' => fake()->paragraphs(3, true),
                'years_experience' => fake()->numberBetween(1, 30),
                'law_school' => fake()->randomElement([
                    'University of the Philippines College of Law',
                    'Ateneo de Manila University School of Law',
                    'University of Santo Tomas Faculty of Civil Law',
                    'De La Salle University College of Law',
                    'San Beda University College of Law',
                    'Far Eastern University Institute of Law',
                ]),
                'graduation_year' => fake()->numberBetween(1990, 2020),
                'rating' => fake()->randomFloat(2, 3.5, 5.0),
                'total_reviews' => fake()->numberBetween(5, 100),
                'total_consultations' => fake()->numberBetween(10, 500),
                'is_verified' => true,
                'is_available' => true,
                'username' => strtolower(str_replace(' ', '', $user->name)) . $i,
                
                // Service offerings - randomly enable services
                'offers_chat_consultation' => fake()->boolean(80), // 80% chance
                'offers_video_consultation' => fake()->boolean(70), // 70% chance
                'offers_document_review' => fake()->boolean(60), // 60% chance
                
                // Chat consultation rates
                'chat_rate_15min' => fake()->randomElement([300, 400, 500, 600, 750]),
                'chat_rate_30min' => fake()->randomElement([500, 600, 750, 900, 1000]),
                'chat_rate_60min' => fake()->randomElement([900, 1000, 1200, 1500, 1800]),
                
                // Video consultation rates (typically higher than chat)
                'video_rate_15min' => fake()->randomElement([500, 600, 750, 900, 1000]),
                'video_rate_30min' => fake()->randomElement([900, 1000, 1200, 1500, 1800]),
                'video_rate_60min' => fake()->randomElement([1500, 1800, 2000, 2500, 3000]),
                
                // Document review minimum price
                'document_review_min_price' => fake()->randomElement([1000, 1500, 2000, 2500, 3000, 3500]),
            ]);

            // Assign multiple specializations (mix of parent and child)
            $numSpecializations = rand(3, 7); // Each lawyer has 3-7 specializations
            
            // Strategy: Pick 1-2 parent specializations, then add specific children from those parents
            $selectedParents = $parentSpecializations->random(rand(1, 2));
            $specializationsToAttach = [];
            
            foreach ($selectedParents as $parent) {
                // Add the parent
                $specializationsToAttach[] = $parent->id;
                
                // Add 2-4 children from this parent
                $childrenCount = min(rand(2, 4), $parent->children->count());
                $selectedChildren = $parent->children->random($childrenCount);
                
                foreach ($selectedChildren as $child) {
                    $specializationsToAttach[] = $child->id;
                }
            }
            
            // Ensure we don't exceed the desired number
            $specializationsToAttach = array_unique($specializationsToAttach);
            $specializationsToAttach = array_slice($specializationsToAttach, 0, $numSpecializations);
            
            $lawyer->specializations()->attach($specializationsToAttach);

            // Create availability schedule (Monday to Friday, 9 AM - 5 PM)
            for ($day = 1; $day <= 5; $day++) {
                $lawyer->availabilitySchedules()->create([
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'is_available' => true,
                ]);
            }
        }

        $this->command->info('Created 20 sample lawyers with multiple specializations and availability schedules.');
    }
}

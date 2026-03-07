<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LawyerProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'ibp_number' => fake()->unique()->numerify('IBP-####-####'),
            'bio' => fake()->paragraphs(3, true),
            'years_experience' => fake()->numberBetween(1, 30),
            'rate_per_15min' => fake()->randomElement([500, 750, 1000, 1500, 2000, 2500, 3000]),
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
            'username' => fake()->unique()->userName(),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Refund;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AdminDashboardDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        $this->command->info('Populating admin dashboard with comprehensive data...');
        
        // Get existing users
        $clients = User::where('role', 'client')->get();
        $lawyers = User::where('role', 'lawyer')->take(10)->get();
        
        if ($clients->count() === 0 || $lawyers->count() === 0) {
            $this->command->error('Please run LawyerSeeder first!');
            return;
        }
        
        // 1. CREATE MORE CLIENTS (50 total clients)
        $this->command->info('Creating additional clients...');
        for ($i = 1; $i <= 45; $i++) {
            User::create([
                'name' => $faker->name(),
                'email' => 'client' . ($i + 5) . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'client',
                'phone' => '09' . $faker->numerify('#########'),
                'location' => $faker->randomElement(['Manila', 'Quezon City', 'Makati', 'Cebu City', 'Davao City']),
                'email_verified_at' => now()->subDays(rand(1, 90)),
                'onboarding_completed_at' => now()->subDays(rand(1, 90)),
                'created_at' => now()->subDays(rand(1, 180)),
            ]);
        }
        
        $allClients = User::where('role', 'client')->get();
        $this->command->info('✓ Total clients: ' . $allClients->count());
        
        // 2. CREATE HISTORICAL CONSULTATIONS (Last 6 months)
        $this->command->info('Creating historical consultations...');
        $consultationTypes = ['chat', 'video'];
        $durations = [15, 30, 60];
        $statuses = ['completed' => 70, 'cancelled' => 15, 'in_progress' => 10, 'scheduled' => 5];
        
        $consultationTitles = [
            'Employment Contract Review',
            'Property Dispute Consultation',
            'Business Formation Advice',
            'Family Law - Divorce Consultation',
            'Criminal Defense Consultation',
            'Tax Law Consultation',
            'Immigration Law Advice',
            'Intellectual Property Consultation',
            'Real Estate Transaction Review',
            'Labor Law Dispute',
            'Corporate Compliance Review',
            'Contract Drafting Assistance',
            'Debt Collection Advice',
            'Personal Injury Consultation',
            'Estate Planning Consultation',
        ];
        
        $totalConsultations = 0;
        
        // Create consultations for the last 6 months
        for ($month = 0; $month < 6; $month++) {
            $consultationsThisMonth = rand(30, 50); // 30-50 consultations per month
            
            for ($i = 0; $i < $consultationsThisMonth; $i++) {
                $client = $allClients->random();
                $lawyer = $lawyers->random();
                $type = $faker->randomElement($consultationTypes);
                $duration = $faker->randomElement($durations);
                
                // Determine status based on weighted probability
                $rand = rand(1, 100);
                if ($rand <= 70) {
                    $status = 'completed';
                } elseif ($rand <= 85) {
                    $status = 'cancelled';
                } elseif ($rand <= 95) {
                    $status = 'in_progress';
                } else {
                    $status = 'scheduled';
                }
                
                // Calculate dates
                $daysAgo = ($month * 30) + rand(0, 29);
                $scheduledAt = now()->subDays($daysAgo)->subHours(rand(8, 20));
                
                // Calculate amount based on type and duration
                $baseRate = $type === 'chat' ? 500 : 800;
                $amount = $baseRate + ($duration * 10);
                
                $consultation = Consultation::create([
                    'client_id' => $client->id,
                    'lawyer_id' => $lawyer->id,
                    'consultation_type' => $type,
                    'duration' => $duration,
                    'status' => $status,
                    'scheduled_at' => $scheduledAt,
                    'started_at' => in_array($status, ['completed', 'in_progress']) ? $scheduledAt : null,
                    'completed_at' => $status === 'completed' ? $scheduledAt->copy()->addMinutes($duration) : null,
                    'title' => $faker->randomElement($consultationTitles),
                    'client_notes' => $faker->sentence(rand(10, 20)),
                    'rate' => $amount,
                    'total_amount' => $amount,
                    'created_at' => $scheduledAt->copy()->subDays(rand(1, 7)),
                ]);
                
                // Create transaction if consultation was paid
                if (in_array($status, ['completed', 'in_progress', 'scheduled'])) {
                    $paymentMethod = $faker->randomElement(['gcash', 'paymaya', 'card']);
                    
                    Transaction::create([
                        'consultation_id' => $consultation->id,
                        'user_id' => $client->id,
                        'amount' => $amount,
                        'platform_fee' => 0,
                        'payment_method' => $paymentMethod,
                        'paymongo_payment_intent_id' => 'pi_' . $faker->uuid(),
                        'status' => 'completed',
                        'created_at' => $scheduledAt->copy()->subHours(rand(1, 24)),
                    ]);
                }
                
                // Add messages for completed consultations
                if ($status === 'completed') {
                    $numMessages = rand(10, 30);
                    for ($m = 0; $m < $numMessages; $m++) {
                        $isClient = $m % 2 === 0;
                        $sender = $isClient ? $client : $lawyer;
                        
                        ConsultationMessage::create([
                            'consultation_id' => $consultation->id,
                            'sender_id' => $sender->id,
                            'message' => $faker->sentence(rand(10, 25)),
                            'created_at' => $scheduledAt->copy()->addMinutes($m * 2),
                            'read_at' => $scheduledAt->copy()->addMinutes($m * 2 + 1),
                        ]);
                    }
                    
                    // Add review (70% chance)
                    if (rand(1, 100) <= 70) {
                        Review::create([
                            'consultation_id' => $consultation->id,
                            'client_id' => $client->id,
                            'lawyer_profile_id' => $lawyer->lawyerProfile->id,
                            'rating' => $faker->randomFloat(1, 3.5, 5.0),
                            'comment' => $faker->paragraph(rand(2, 4)),
                            'created_at' => $scheduledAt->copy()->addHours(rand(1, 48)),
                        ]);
                    }
                }
                
                $totalConsultations++;
            }
        }
        
        $this->command->info('✓ Created ' . $totalConsultations . ' historical consultations');
        
        // 3. CREATE REFUND REQUESTS (Various statuses)
        $this->command->info('Creating refund requests...');
        $completedConsultations = Consultation::where('status', 'completed')
            ->whereHas('transaction')
            ->inRandomOrder()
            ->take(15)
            ->get();
        
        $refundStatuses = [
            'pending' => 5,
            'approved' => 4,
            'processing' => 3,
            'completed' => 2,
            'rejected' => 1,
        ];
        
        $refundCount = 0;
        foreach ($refundStatuses as $status => $count) {
            for ($i = 0; $i < $count; $i++) {
                if ($refundCount >= $completedConsultations->count()) break;
                
                $consultation = $completedConsultations[$refundCount];
                $transaction = $consultation->transaction;
                
                $refund = Refund::create([
                    'consultation_id' => $consultation->id,
                    'transaction_id' => $transaction->id,
                    'user_id' => $consultation->client_id,
                    'refund_type' => 'full',
                    'refund_amount' => $transaction->amount,
                    'original_amount' => $transaction->amount,
                    'reason' => $faker->randomElement([
                        'lawyer_cancelled',
                        'client_cancelled',
                        'dispute',
                        'other',
                    ]),
                    'detailed_reason' => $faker->randomElement([
                        'Lawyer did not show up',
                        'Poor service quality',
                        'Technical issues during consultation',
                        'Consultation was too short',
                        'Lawyer was unprepared',
                    ]),
                    'status' => $status,
                    'approved_at' => in_array($status, ['approved', 'processing', 'completed']) ? now()->subDays(rand(1, 20)) : null,
                    'processed_at' => in_array($status, ['processing', 'completed']) ? now()->subDays(rand(1, 10)) : null,
                    'rejected_at' => $status === 'rejected' ? now()->subDays(rand(1, 15)) : null,
                    'rejection_reason' => $status === 'rejected' ? 'Consultation was completed as agreed' : null,
                ]);
                
                $refundCount++;
            }
        }
        
        $this->command->info('✓ Created ' . $refundCount . ' refund requests');
        
        // 4. CREATE NEWSLETTER SUBSCRIBERS
        $this->command->info('Creating newsletter subscribers...');
        for ($i = 1; $i <= 100; $i++) {
            NewsletterSubscriber::create([
                'email' => $faker->unique()->safeEmail(),
                'subscribed_at' => now()->subDays(rand(1, 180)),
                'is_subscribed' => rand(1, 100) <= 90, // 90% subscribed
            ]);
        }
        
        $this->command->info('✓ Created 100 newsletter subscribers');
        
        // 5. UPDATE LAWYER STATISTICS
        $this->command->info('Updating lawyer statistics...');
        foreach ($lawyers as $lawyer) {
            $lawyerConsultations = Consultation::where('lawyer_id', $lawyer->id)
                ->where('status', 'completed')
                ->get();
            
            $totalConsultations = $lawyerConsultations->count();
            $totalReviews = Review::where('lawyer_profile_id', $lawyer->lawyerProfile->id)->count();
            $avgRating = Review::where('lawyer_profile_id', $lawyer->lawyerProfile->id)->avg('rating') ?? 4.5;
            
            $lawyer->lawyerProfile->update([
                'total_consultations' => $totalConsultations,
                'total_reviews' => $totalReviews,
                'rating' => round($avgRating, 2),
            ]);
        }
        
        $this->command->info('✓ Updated lawyer statistics');
        
        // 6. CREATE SOME PENDING LAWYER VERIFICATIONS
        $this->command->info('Creating pending lawyer verifications...');
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'name' => $faker->name(),
                'email' => 'pending_lawyer' . $i . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'lawyer',
                'phone' => '09' . $faker->numerify('#########'),
                'location' => $faker->randomElement(['Manila', 'Quezon City', 'Makati']),
                'email_verified_at' => now(),
                'onboarding_completed_at' => now(),
                'created_at' => now()->subDays(rand(1, 14)),
            ]);
            
            $user->lawyerProfile()->create([
                'ibp_number' => $faker->unique()->numerify('IBP-####-####'),
                'bio' => $faker->paragraphs(3, true),
                'years_experience' => $faker->numberBetween(1, 15),
                'law_school' => $faker->randomElement([
                    'University of the Philippines College of Law',
                    'Ateneo de Manila University School of Law',
                    'University of Santo Tomas Faculty of Civil Law',
                ]),
                'graduation_year' => $faker->numberBetween(2005, 2020),
                'is_verified' => false,
                'is_available' => false,
                'username' => strtolower(str_replace(' ', '', $user->name)) . $i,
            ]);
        }
        
        $this->command->info('✓ Created 5 pending lawyer verifications');
        
        // Summary
        $this->command->info('');
        $this->command->info('=== ADMIN DASHBOARD DATA SUMMARY ===');
        $this->command->info('Total Users: ' . User::count());
        $this->command->info('- Clients: ' . User::where('role', 'client')->count());
        $this->command->info('- Lawyers: ' . User::where('role', 'lawyer')->count());
        $this->command->info('- Verified Lawyers: ' . User::where('role', 'lawyer')->whereHas('lawyerProfile', function($q) {
            $q->where('is_verified', true);
        })->count());
        $this->command->info('- Pending Verification: ' . User::where('role', 'lawyer')->whereHas('lawyerProfile', function($q) {
            $q->where('is_verified', false);
        })->count());
        $this->command->info('');
        $this->command->info('Total Consultations: ' . Consultation::count());
        $this->command->info('- Completed: ' . Consultation::where('status', 'completed')->count());
        $this->command->info('- In Progress: ' . Consultation::where('status', 'in_progress')->count());
        $this->command->info('- Scheduled: ' . Consultation::where('status', 'scheduled')->count());
        $this->command->info('- Cancelled: ' . Consultation::where('status', 'cancelled')->count());
        $this->command->info('');
        $this->command->info('Total Transactions: ' . Transaction::count());
        $this->command->info('Total Revenue: ₱' . number_format(Transaction::where('status', 'completed')->sum('amount'), 2));
        $this->command->info('');
        $this->command->info('Total Refunds: ' . Refund::count());
        $this->command->info('- Pending: ' . Refund::where('status', 'pending')->count());
        $this->command->info('- Approved: ' . Refund::where('status', 'approved')->count());
        $this->command->info('- Completed: ' . Refund::where('status', 'completed')->count());
        $this->command->info('- Rejected: ' . Refund::where('status', 'rejected')->count());
        $this->command->info('');
        $this->command->info('Total Reviews: ' . Review::count());
        $this->command->info('Average Rating: ' . round(Review::avg('rating'), 2));
        $this->command->info('');
        $this->command->info('Newsletter Subscribers: ' . NewsletterSubscriber::where('is_subscribed', true)->count());
        $this->command->info('');
    }
}

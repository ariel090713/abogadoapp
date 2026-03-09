<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\ConsultationMessage;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class OngoingConsultationsSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get test client and some lawyers
        $client = User::where('email', 'client@example.com')->first();
        $lawyers = User::where('role', 'lawyer')->take(5)->get();
        
        if (!$client || $lawyers->count() === 0) {
            $this->command->error('Please run LawyerSeeder first!');
            return;
        }
        
        $this->command->info('Creating ongoing consultations with messages and transactions...');
        
        // 1. ONGOING CHAT CONSULTATION (Long conversation - 50+ messages)
        $chatConsultation = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyers[0]->id,
            'type' => 'chat',
            'duration' => 60,
            'status' => 'in_progress',
            'scheduled_at' => now()->subHours(2),
            'started_at' => now()->subHours(2),
            'title' => 'Employment Contract Review',
            'description' => 'Need help reviewing my employment contract and understanding my rights.',
            'amount' => 1500,
        ]);
        
        // Create transaction for chat consultation
        Transaction::create([
            'consultation_id' => $chatConsultation->id,
            'user_id' => $client->id,
            'amount' => 1500,
            'platform_fee' => 0,
            'lawyer_amount' => 1500,
            'payment_method' => 'gcash',
            'payment_intent_id' => 'pi_chat_' . $faker->uuid(),
            'status' => 'completed',
            'paid_at' => now()->subHours(3),
        ]);
        
        // Create long conversation (50 messages over 2 hours)
        $messages = [
            ['sender' => 'client', 'text' => 'Hi Attorney! Thank you for accepting my consultation.'],
            ['sender' => 'lawyer', 'text' => 'Good day! You\'re welcome. I\'ve reviewed your initial description. Could you share the specific clauses in your employment contract that concern you?'],
            ['sender' => 'client', 'text' => 'Yes, I\'m particularly worried about the non-compete clause. It says I can\'t work in the same industry for 2 years after leaving.'],
            ['sender' => 'lawyer', 'text' => 'I see. Non-compete clauses in the Philippines must be reasonable in scope, duration, and geographic area. A 2-year restriction might be considered excessive depending on your role and industry.'],
            ['sender' => 'client', 'text' => 'Really? I thought I had to follow it no matter what.'],
            ['sender' => 'lawyer', 'text' => 'Not necessarily. Philippine jurisprudence has established that non-compete clauses must not be overly restrictive. What is your current position and industry?'],
            ['sender' => 'client', 'text' => 'I\'m a software developer in a tech startup. The clause says I can\'t work for any tech company in Metro Manila for 2 years.'],
            ['sender' => 'lawyer', 'text' => 'That\'s quite broad. For a software developer, such a restriction could effectively prevent you from earning a livelihood in your field. Courts generally disfavor such overly broad restrictions.'],
            ['sender' => 'client', 'text' => 'What about the confidentiality clause? It seems very strict.'],
            ['sender' => 'lawyer', 'text' => 'Confidentiality clauses are generally enforceable as long as they protect legitimate business interests. Can you share what it covers?'],
            ['sender' => 'client', 'text' => 'It says I can\'t disclose any information about the company, including general business practices, even after I leave.'],
            ['sender' => 'lawyer', 'text' => 'The key issue is whether it covers trade secrets and proprietary information, or general knowledge and skills you\'ve acquired. The latter would be problematic.'],
            ['sender' => 'client', 'text' => 'It doesn\'t specify. It just says "any and all information."'],
            ['sender' => 'lawyer', 'text' => 'That\'s overly broad. A properly drafted confidentiality clause should specifically define what constitutes confidential information.'],
            ['sender' => 'client', 'text' => 'There\'s also a clause about intellectual property. All work I create, even outside office hours, belongs to the company.'],
            ['sender' => 'lawyer', 'text' => 'That\'s concerning. Under Philippine law, particularly the Intellectual Property Code, employers can only claim ownership of works created within the scope of employment.'],
            ['sender' => 'client', 'text' => 'So if I create a personal project on weekends, they can\'t claim it?'],
            ['sender' => 'lawyer', 'text' => 'Correct, as long as it\'s not related to your work duties and doesn\'t use company resources. However, the contract clause might create disputes. It should be clarified.'],
            ['sender' => 'client', 'text' => 'What should I do? I already signed the contract.'],
            ['sender' => 'lawyer', 'text' => 'You have several options: 1) Negotiate with your employer to modify the problematic clauses, 2) Document everything carefully, or 3) Seek to have the clauses declared unenforceable if they violate your rights.'],
            ['sender' => 'client', 'text' => 'Can I negotiate even after signing?'],
            ['sender' => 'lawyer', 'text' => 'Yes, contracts can be amended by mutual agreement. Many employers are willing to clarify or modify clauses when concerns are raised professionally.'],
            ['sender' => 'client', 'text' => 'What about the termination clause? It says they can terminate me without cause with just 30 days notice.'],
            ['sender' => 'lawyer', 'text' => 'That depends on whether you\'re a regular or probationary employee. For regular employees, termination must be for just or authorized causes under the Labor Code.'],
            ['sender' => 'client', 'text' => 'I\'m still on probation, 3 months in.'],
            ['sender' => 'lawyer', 'text' => 'During probation, the employer has more flexibility, but they must still inform you of the standards you need to meet and give you a chance to meet them.'],
            ['sender' => 'client', 'text' => 'They never gave me any written standards.'],
            ['sender' => 'lawyer', 'text' => 'That\'s a red flag. The employer must inform you of the reasonable standards for regularization at the start of probation. Without this, you might have grounds to contest any termination.'],
            ['sender' => 'client', 'text' => 'Should I ask for these standards in writing now?'],
            ['sender' => 'lawyer', 'text' => 'Absolutely. Send a polite email requesting clarification of the performance standards for regularization. Keep a copy of this request and their response.'],
            ['sender' => 'client', 'text' => 'There\'s also a clause about overtime. It says overtime is "as needed" but doesn\'t mention compensation.'],
            ['sender' => 'lawyer', 'text' => 'That\'s problematic. Under the Labor Code, overtime work must be compensated at 125% of regular pay on ordinary days, and higher rates on rest days and holidays.'],
            ['sender' => 'client', 'text' => 'They told me my salary already includes overtime pay.'],
            ['sender' => 'lawyer', 'text' => 'That\'s only valid if you\'re a managerial employee or if there\'s a clear agreement. For rank-and-file employees, overtime must be separately compensated.'],
            ['sender' => 'client', 'text' => 'I\'m definitely not managerial. I report to a team lead.'],
            ['sender' => 'lawyer', 'text' => 'Then you\'re entitled to overtime pay. Keep records of your actual working hours, including any overtime.'],
            ['sender' => 'client', 'text' => 'What about the benefits? The contract mentions "statutory benefits only."'],
            ['sender' => 'lawyer', 'text' => 'Statutory benefits include SSS, PhilHealth, Pag-IBIG, and 13th month pay. These are mandatory. Any additional benefits would be at the employer\'s discretion.'],
            ['sender' => 'client', 'text' => 'No health insurance or leave credits beyond the minimum?'],
            ['sender' => 'lawyer', 'text' => 'If the contract says "statutory benefits only," then yes, only the minimum required by law. However, you can negotiate for additional benefits.'],
            ['sender' => 'client', 'text' => 'The contract also has a clause about social media. I can\'t post anything about the company without approval.'],
            ['sender' => 'lawyer', 'text' => 'Social media policies must balance the employer\'s interests with your freedom of expression. Restrictions should be reasonable and related to protecting confidential information or the company\'s reputation.'],
            ['sender' => 'client', 'text' => 'It says I can\'t even mention that I work there on LinkedIn.'],
            ['sender' => 'lawyer', 'text' => 'That seems excessive. Merely stating your employment on professional networking sites is generally acceptable unless there\'s a specific security concern.'],
            ['sender' => 'client', 'text' => 'There\'s a liquidated damages clause too. If I leave before 2 years, I have to pay ₱100,000.'],
            ['sender' => 'lawyer', 'text' => 'Liquidated damages clauses are valid only if they represent a reasonable pre-estimate of actual damages. ₱100,000 seems excessive unless the company invested significantly in your training.'],
            ['sender' => 'client', 'text' => 'They did send me to a 2-week training course that cost about ₱50,000.'],
            ['sender' => 'lawyer', 'text' => 'Then a proportional amount might be reasonable, but ₱100,000 for a ₱50,000 training seems high. The clause should specify what it covers and be proportionate.'],
            ['sender' => 'client', 'text' => 'What if I want to leave now? Do I have to pay?'],
            ['sender' => 'lawyer', 'text' => 'You can resign anytime by giving proper notice (usually 30 days). Whether you\'d have to pay the liquidated damages depends on whether the clause is enforceable and what it specifically covers.'],
            ['sender' => 'client', 'text' => 'This is all very helpful. What should be my next steps?'],
            ['sender' => 'lawyer', 'text' => 'Here\'s what I recommend: 1) Request written performance standards for regularization, 2) Keep detailed records of your working hours, 3) Document all communications with your employer, 4) Consider requesting a meeting to discuss clarifying the problematic contract clauses.'],
            ['sender' => 'client', 'text' => 'Should I mention that I consulted a lawyer?'],
            ['sender' => 'lawyer', 'text' => 'That\'s your choice. You can frame it as seeking clarification to ensure mutual understanding, without necessarily mentioning legal consultation. The goal is to resolve issues amicably.'],
            ['sender' => 'client', 'text' => 'What if they refuse to modify anything?'],
            ['sender' => 'lawyer', 'text' => 'Then you\'ll need to decide whether to continue with the employment under these terms, or look for other opportunities. If they later try to enforce unreasonable clauses, you can challenge them.'],
            ['sender' => 'client', 'text' => 'Can I get a copy of your analysis in writing?'],
            ['sender' => 'lawyer', 'text' => 'Yes, I can prepare a brief legal opinion summarizing our discussion and my recommendations. This will be useful for your records and any future discussions with your employer.'],
            ['sender' => 'client', 'text' => 'That would be great! How much would that cost?'],
            ['sender' => 'lawyer', 'text' => 'I can include a brief written summary as part of this consultation. For a more detailed legal opinion, we can discuss a separate engagement.'],
            ['sender' => 'client', 'text' => 'The brief summary would be perfect for now. Thank you so much for all this information!'],
            ['sender' => 'lawyer', 'text' => 'You\'re welcome! I\'ll prepare the summary and send it to you within 24 hours. Feel free to reach out if you have any follow-up questions.'],
            ['sender' => 'client', 'text' => 'Will do. Thanks again, Attorney!'],
        ];
        
        $baseTime = now()->subHours(2);
        foreach ($messages as $index => $msg) {
            $sender = $msg['sender'] === 'client' ? $client : $lawyers[0];
            $minutesAgo = 120 - ($index * 2); // Spread over 2 hours
            
            ConsultationMessage::create([
                'consultation_id' => $chatConsultation->id,
                'sender_id' => $sender->id,
                'message' => $msg['text'],
                'created_at' => $baseTime->copy()->addMinutes(120 - $minutesAgo),
                'read_at' => $msg['sender'] === 'lawyer' ? $baseTime->copy()->addMinutes(121 - $minutesAgo) : null,
            ]);
        }
        
        $this->command->info('✓ Created ongoing chat consultation with 50+ messages');
        
        // 2. ONGOING VIDEO CONSULTATION (Currently in progress)
        $videoConsultation = Consultation::create([
            'client_id' => $client->id,
            'lawyer_id' => $lawyers[1]->id,
            'type' => 'video',
            'duration' => 30,
            'status' => 'in_progress',
            'scheduled_at' => now()->subMinutes(15),
            'started_at' => now()->subMinutes(15),
            'title' => 'Property Dispute Consultation',
            'description' => 'Boundary dispute with neighbor regarding property line.',
            'amount' => 1200,
            'video_room_name' => 'room_' . $faker->uuid(),
        ]);
        
        // Create transaction for video consultation
        Transaction::create([
            'consultation_id' => $videoConsultation->id,
            'user_id' => $client->id,
            'amount' => 1200,
            'platform_fee' => 0,
            'lawyer_amount' => 1200,
            'payment_method' => 'card',
            'payment_intent_id' => 'pi_video_' . $faker->uuid(),
            'status' => 'completed',
            'paid_at' => now()->subMinutes(30),
        ]);
        
        // Add some chat messages during video call
        $videoMessages = [
            ['sender' => 'lawyer', 'text' => 'Good day! I can see you now. Can you hear me clearly?'],
            ['sender' => 'client', 'text' => 'Yes, Attorney! I can hear you well.'],
            ['sender' => 'lawyer', 'text' => 'Great! Let me share my screen to show you the property documents.'],
            ['sender' => 'client', 'text' => 'I can see the documents now.'],
            ['sender' => 'lawyer', 'text' => 'Based on the survey plan, the boundary line is clearly marked here.'],
            ['sender' => 'client', 'text' => 'My neighbor claims the fence should be 2 meters to the left.'],
            ['sender' => 'lawyer', 'text' => 'Do you have the original title and survey documents? We need to verify the technical descriptions.'],
            ['sender' => 'client', 'text' => 'Yes, I have them. Should I show them on camera?'],
            ['sender' => 'lawyer', 'text' => 'Yes please, hold them up to the camera so I can see the details.'],
            ['sender' => 'client', 'text' => 'Here they are. Can you see them clearly?'],
        ];
        
        $videoBaseTime = now()->subMinutes(15);
        foreach ($videoMessages as $index => $msg) {
            $sender = $msg['sender'] === 'client' ? $client : $lawyers[1];
            
            ConsultationMessage::create([
                'consultation_id' => $videoConsultation->id,
                'sender_id' => $sender->id,
                'message' => $msg['text'],
                'created_at' => $videoBaseTime->copy()->addMinutes($index),
                'read_at' => now(),
            ]);
        }
        
        $this->command->info('✓ Created ongoing video consultation (currently in progress)');
        
        // 3. COMPLETED CONSULTATIONS WITH TRANSACTIONS (for transaction history)
        $completedConsultations = [
            [
                'type' => 'chat',
                'duration' => 30,
                'title' => 'Contract Review - Freelance Agreement',
                'amount' => 750,
                'payment_method' => 'gcash',
                'completed_days_ago' => 5,
            ],
            [
                'type' => 'video',
                'duration' => 60,
                'title' => 'Family Law - Child Custody Advice',
                'amount' => 2000,
                'payment_method' => 'paymaya',
                'completed_days_ago' => 10,
            ],
            [
                'type' => 'chat',
                'duration' => 15,
                'title' => 'Quick Legal Question - Lease Agreement',
                'amount' => 500,
                'payment_method' => 'card',
                'completed_days_ago' => 15,
            ],
            [
                'type' => 'video',
                'duration' => 30,
                'title' => 'Business Formation Consultation',
                'amount' => 1500,
                'payment_method' => 'gcash',
                'completed_days_ago' => 20,
            ],
            [
                'type' => 'chat',
                'duration' => 60,
                'title' => 'Employment Termination Issue',
                'amount' => 1200,
                'payment_method' => 'card',
                'completed_days_ago' => 25,
            ],
        ];
        
        foreach ($completedConsultations as $index => $data) {
            $lawyer = $lawyers[$index % $lawyers->count()];
            $scheduledAt = now()->subDays($data['completed_days_ago'])->subHours(1);
            
            $consultation = Consultation::create([
                'client_id' => $client->id,
                'lawyer_id' => $lawyer->id,
                'type' => $data['type'],
                'duration' => $data['duration'],
                'status' => 'completed',
                'scheduled_at' => $scheduledAt,
                'started_at' => $scheduledAt,
                'completed_at' => $scheduledAt->copy()->addMinutes($data['duration']),
                'title' => $data['title'],
                'description' => 'Consultation regarding ' . strtolower($data['title']),
                'amount' => $data['amount'],
            ]);
            
            // Create transaction
            Transaction::create([
                'consultation_id' => $consultation->id,
                'user_id' => $client->id,
                'amount' => $data['amount'],
                'platform_fee' => 0,
                'lawyer_amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'payment_intent_id' => 'pi_completed_' . $faker->uuid(),
                'status' => 'completed',
                'paid_at' => $scheduledAt->copy()->subHours(2),
            ]);
            
            // Add some messages
            $numMessages = rand(5, 15);
            for ($i = 0; $i < $numMessages; $i++) {
                $isClient = $i % 2 === 0;
                $sender = $isClient ? $client : $lawyer;
                
                ConsultationMessage::create([
                    'consultation_id' => $consultation->id,
                    'sender_id' => $sender->id,
                    'message' => $isClient 
                        ? $faker->sentence(rand(10, 20))
                        : 'Based on your situation, ' . $faker->sentence(rand(15, 25)),
                    'created_at' => $scheduledAt->copy()->addMinutes($i * 2),
                    'read_at' => $scheduledAt->copy()->addMinutes($i * 2 + 1),
                ]);
            }
        }
        
        $this->command->info('✓ Created 5 completed consultations with transactions');
        
        $this->command->info('');
        $this->command->info('Summary:');
        $this->command->info('- 1 ongoing chat consultation (2 hours, 50+ messages)');
        $this->command->info('- 1 ongoing video consultation (15 minutes, currently in progress)');
        $this->command->info('- 5 completed consultations with full transaction history');
        $this->command->info('');
        $this->command->info('Test with: client@example.com / password');
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DocumentTemplate;
use App\Models\LawyerDocumentService;
use Illuminate\Database\Seeder;

class LawyerDocumentServiceSeeder extends Seeder
{
    public function run(): void
    {
        // Get first verified lawyer
        $lawyer = User::where('role', 'lawyer')
            ->whereHas('lawyerProfile', function ($q) {
                $q->where('is_verified', true);
            })
            ->first();

        if (!$lawyer) {
            $this->command->warn('No verified lawyer found. Please run LawyerSeeder first.');
            return;
        }

        // Get some document templates
        $templates = DocumentTemplate::whereIn('name', [
            'Affidavit of Loss',
            'Deed of Absolute Sale (Personal Property)',
            'Contract of Lease (Residential)',
            'Special Power of Attorney',
            'Promissory Note'
        ])->get();

        if ($templates->isEmpty()) {
            $this->command->warn('No document templates found. Please run DocumentTemplateSeeder first.');
            return;
        }

        $services = [
            [
                'template_id' => $templates->where('name', 'Affidavit of Loss')->first()?->id,
                'name' => 'Affidavit of Loss',
                'category' => 'affidavit',
                'price' => 1500,
                'estimated_completion_days' => 2,
                'estimated_client_time' => 10,
                'revisions_allowed' => 2,
                'description' => 'Legal affidavit for lost documents, IDs, or important papers',
                'form_fields' => json_encode([
                    'fields' => [
                        [
                            'id' => 'full_name',
                            'type' => 'text',
                            'label' => 'Full Name',
                            'placeholder' => 'Enter your full legal name',
                            'required' => true,
                        ],
                        [
                            'id' => 'address',
                            'type' => 'textarea',
                            'label' => 'Complete Address',
                            'placeholder' => 'Enter your complete residential address',
                            'required' => true,
                            'rows' => 3,
                        ],
                        [
                            'id' => 'item_lost',
                            'type' => 'text',
                            'label' => 'Item Lost',
                            'placeholder' => 'e.g., Driver\'s License, Passport, etc.',
                            'required' => true,
                        ],
                        [
                            'id' => 'date_lost',
                            'type' => 'date',
                            'label' => 'Date Lost',
                            'required' => true,
                        ],
                        [
                            'id' => 'circumstances',
                            'type' => 'textarea',
                            'label' => 'Circumstances of Loss',
                            'placeholder' => 'Describe how and where the item was lost',
                            'required' => true,
                            'rows' => 4,
                        ],
                    ]
                ]),
                'is_active' => true,
            ],
            [
                'template_id' => $templates->where('name', 'Deed of Absolute Sale (Personal Property)')->first()?->id,
                'name' => 'Deed of Absolute Sale',
                'category' => 'deed',
                'price' => 3500,
                'estimated_completion_days' => 5,
                'estimated_client_time' => 20,
                'revisions_allowed' => 3,
                'description' => 'Transfer of ownership for personal property',
                'form_fields' => json_encode([
                    'fields' => [
                        [
                            'id' => 'seller_name',
                            'type' => 'text',
                            'label' => 'Seller Full Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'buyer_name',
                            'type' => 'text',
                            'label' => 'Buyer Full Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'property_type',
                            'type' => 'select',
                            'label' => 'Property Type',
                            'required' => true,
                            'options' => ['Real Property', 'Vehicle', 'Personal Property', 'Other'],
                        ],
                        [
                            'id' => 'property_description',
                            'type' => 'textarea',
                            'label' => 'Property Description',
                            'placeholder' => 'Detailed description of the property being sold',
                            'required' => true,
                            'rows' => 4,
                        ],
                        [
                            'id' => 'sale_price',
                            'type' => 'number',
                            'label' => 'Sale Price (PHP)',
                            'required' => true,
                            'min' => 0,
                        ],
                    ]
                ]),
                'is_active' => true,
            ],
            [
                'template_id' => $templates->where('name', 'Contract of Lease (Residential)')->first()?->id,
                'name' => 'Contract of Lease',
                'category' => 'contract',
                'price' => 2500,
                'estimated_completion_days' => 3,
                'estimated_client_time' => 15,
                'revisions_allowed' => 2,
                'description' => 'Rental agreement for residential properties',
                'form_fields' => json_encode([
                    'fields' => [
                        [
                            'id' => 'lessor_name',
                            'type' => 'text',
                            'label' => 'Lessor (Owner) Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'lessee_name',
                            'type' => 'text',
                            'label' => 'Lessee (Tenant) Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'property_address',
                            'type' => 'textarea',
                            'label' => 'Property Address',
                            'required' => true,
                            'rows' => 3,
                        ],
                        [
                            'id' => 'monthly_rent',
                            'type' => 'number',
                            'label' => 'Monthly Rent (PHP)',
                            'required' => true,
                            'min' => 0,
                        ],
                        [
                            'id' => 'lease_period',
                            'type' => 'number',
                            'label' => 'Lease Period (months)',
                            'required' => true,
                            'min' => 1,
                        ],
                        [
                            'id' => 'start_date',
                            'type' => 'date',
                            'label' => 'Lease Start Date',
                            'required' => true,
                        ],
                    ]
                ]),
                'is_active' => true,
            ],
            [
                'template_id' => $templates->where('name', 'Special Power of Attorney')->first()?->id,
                'name' => 'Special Power of Attorney',
                'category' => 'power_of_attorney',
                'price' => 2000,
                'estimated_completion_days' => 2,
                'estimated_client_time' => 12,
                'revisions_allowed' => 2,
                'description' => 'Authorization to act on behalf of another person for specific matters',
                'form_fields' => json_encode([
                    'fields' => [
                        [
                            'id' => 'principal_name',
                            'type' => 'text',
                            'label' => 'Principal (Grantor) Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'attorney_name',
                            'type' => 'text',
                            'label' => 'Attorney-in-Fact Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'purpose',
                            'type' => 'textarea',
                            'label' => 'Purpose/Specific Powers',
                            'placeholder' => 'Describe the specific powers being granted',
                            'required' => true,
                            'rows' => 4,
                        ],
                        [
                            'id' => 'duration',
                            'type' => 'select',
                            'label' => 'Duration',
                            'required' => true,
                            'options' => ['Until Revoked', 'Specific Date', 'Specific Transaction'],
                        ],
                    ]
                ]),
                'is_active' => true,
            ],
            [
                'template_id' => $templates->where('name', 'Promissory Note')->first()?->id,
                'name' => 'Promissory Note',
                'category' => 'letter',
                'price' => 1800,
                'estimated_completion_days' => 1,
                'estimated_client_time' => 10,
                'revisions_allowed' => 2,
                'description' => 'Written promise to pay a specified amount of money',
                'form_fields' => json_encode([
                    'fields' => [
                        [
                            'id' => 'borrower_name',
                            'type' => 'text',
                            'label' => 'Borrower Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'lender_name',
                            'type' => 'text',
                            'label' => 'Lender Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'loan_amount',
                            'type' => 'number',
                            'label' => 'Loan Amount (PHP)',
                            'required' => true,
                            'min' => 0,
                        ],
                        [
                            'id' => 'interest_rate',
                            'type' => 'number',
                            'label' => 'Interest Rate (%)',
                            'required' => false,
                            'min' => 0,
                            'max' => 100,
                        ],
                        [
                            'id' => 'payment_date',
                            'type' => 'date',
                            'label' => 'Payment Due Date',
                            'required' => true,
                        ],
                    ]
                ]),
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            if ($service['template_id']) {
                LawyerDocumentService::create([
                    'lawyer_id' => $lawyer->id,
                    'template_id' => $service['template_id'],
                    'name' => $service['name'],
                    'category' => $service['category'],
                    'price' => $service['price'],
                    'estimated_completion_days' => $service['estimated_completion_days'],
                    'estimated_client_time' => $service['estimated_client_time'],
                    'revisions_allowed' => $service['revisions_allowed'],
                    'description' => $service['description'],
                    'form_fields' => $service['form_fields'],
                    'is_active' => $service['is_active'],
                ]);
            }
        }

        $this->command->info('✓ Created 5 document services for ' . $lawyer->name);
    }
}

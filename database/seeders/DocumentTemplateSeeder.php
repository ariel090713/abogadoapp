<?php

namespace Database\Seeders;

use App\Models\DocumentTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin user (assuming first user is admin)
        $admin = User::where('role', 'admin')->first() ?? User::first();

        $templates = [
            // AFFIDAVITS
            [
                'name' => 'Affidavit of Loss',
                'description' => 'Legal document declaring the loss of important documents or items',
                'category' => 'Affidavits',
                'form_fields' => [
                    'fields' => [
                        [
                            'id' => 'full_name',
                            'type' => 'text',
                            'label' => 'Full Name',
                            'placeholder' => 'Juan Dela Cruz',
                            'required' => true,
                            'help_text' => 'Enter your complete legal name'
                        ],
                        [
                            'id' => 'age',
                            'type' => 'number',
                            'label' => 'Age',
                            'placeholder' => '25',
                            'required' => true,
                        ],
                        [
                            'id' => 'civil_status',
                            'type' => 'select',
                            'label' => 'Civil Status',
                            'required' => true,
                            'options' => ['Single', 'Married', 'Widowed', 'Separated']
                        ],
                        [
                            'id' => 'address',
                            'type' => 'textarea',
                            'label' => 'Complete Address',
                            'placeholder' => 'House No., Street, Barangay, City, Province',
                            'required' => true,
                            'rows' => 3
                        ],
                        [
                            'id' => 'item_lost',
                            'type' => 'text',
                            'label' => 'Item/Document Lost',
                            'placeholder' => 'e.g., Driver\'s License, Birth Certificate',
                            'required' => true,
                        ],
                        [
                            'id' => 'date_lost',
                            'type' => 'date',
                            'label' => 'Date of Loss',
                            'required' => true,
                        ],
                        [
                            'id' => 'place_lost',
                            'type' => 'text',
                            'label' => 'Place Where Lost',
                            'placeholder' => 'e.g., SM Mall Manila',
                            'required' => true,
                        ],
                        [
                            'id' => 'circumstances',
                            'type' => 'textarea',
                            'label' => 'Circumstances of Loss',
                            'placeholder' => 'Describe how the item was lost',
                            'required' => true,
                            'rows' => 4
                        ],
                    ]
                ],
                'sample_output' => 'AFFIDAVIT OF LOSS

I, {{full_name}}, {{age}} years old, {{civil_status}}, Filipino, and a resident of {{address}}, after having been duly sworn in accordance with law, hereby depose and state:

1. That I am the owner of {{item_lost}};

2. That on {{date_lost}}, I lost the said {{item_lost}} at {{place_lost}};

3. That the circumstances surrounding the loss are as follows: {{circumstances}};

4. That despite diligent efforts, I have been unable to locate the said {{item_lost}};

5. That I am executing this affidavit to attest to the truth of the foregoing and for whatever legal purpose it may serve.

IN WITNESS WHEREOF, I have hereunto affixed my signature this ___ day of ________, 20___ at ____________, Philippines.

                                        {{full_name}}
                                        Affiant

SUBSCRIBED AND SWORN to before me this ___ day of ________, 20___ at ____________, Philippines.',
                'is_active' => true,
                'created_by' => $admin->id,
            ],

            [
                'name' => 'Affidavit of Undertaking',
                'description' => 'Document where affiant commits to fulfill certain obligations',
                'category' => 'Affidavits',
                'form_fields' => [
                    'fields' => [
                        [
                            'id' => 'full_name',
                            'type' => 'text',
                            'label' => 'Full Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'age',
                            'type' => 'number',
                            'label' => 'Age',
                            'required' => true,
                        ],
                        [
                            'id' => 'address',
                            'type' => 'textarea',
                            'label' => 'Complete Address',
                            'required' => true,
                            'rows' => 3
                        ],
                        [
                            'id' => 'undertaking',
                            'type' => 'textarea',
                            'label' => 'Undertaking/Commitment',
                            'placeholder' => 'Describe what you are committing to do',
                            'required' => true,
                            'rows' => 5
                        ],
                        [
                            'id' => 'purpose',
                            'type' => 'text',
                            'label' => 'Purpose of Undertaking',
                            'placeholder' => 'e.g., For employment, business permit',
                            'required' => true,
                        ],
                    ]
                ],
                'sample_output' => null,
                'is_active' => true,
                'created_by' => $admin->id,
            ],

            // CONTRACTS
            [
                'name' => 'Contract of Lease (Residential)',
                'description' => 'Rental agreement for residential properties',
                'category' => 'Contracts',
                'form_fields' => [
                    'fields' => [
                        [
                            'id' => 'lessor_name',
                            'type' => 'text',
                            'label' => 'Lessor Name (Owner)',
                            'required' => true,
                        ],
                        [
                            'id' => 'lessor_address',
                            'type' => 'textarea',
                            'label' => 'Lessor Address',
                            'required' => true,
                            'rows' => 2
                        ],
                        [
                            'id' => 'lessee_name',
                            'type' => 'text',
                            'label' => 'Lessee Name (Tenant)',
                            'required' => true,
                        ],
                        [
                            'id' => 'lessee_address',
                            'type' => 'textarea',
                            'label' => 'Lessee Address',
                            'required' => true,
                            'rows' => 2
                        ],
                        [
                            'id' => 'property_address',
                            'type' => 'textarea',
                            'label' => 'Property Address',
                            'placeholder' => 'Complete address of property to be leased',
                            'required' => true,
                            'rows' => 3
                        ],
                        [
                            'id' => 'monthly_rent',
                            'type' => 'number',
                            'label' => 'Monthly Rent (PHP)',
                            'placeholder' => '10000',
                            'required' => true,
                        ],
                        [
                            'id' => 'security_deposit',
                            'type' => 'number',
                            'label' => 'Security Deposit (PHP)',
                            'placeholder' => '20000',
                            'required' => true,
                        ],
                        [
                            'id' => 'lease_period',
                            'type' => 'text',
                            'label' => 'Lease Period',
                            'placeholder' => 'e.g., 1 year, 6 months',
                            'required' => true,
                        ],
                        [
                            'id' => 'start_date',
                            'type' => 'date',
                            'label' => 'Lease Start Date',
                            'required' => true,
                        ],
                        [
                            'id' => 'payment_due',
                            'type' => 'text',
                            'label' => 'Payment Due Date',
                            'placeholder' => 'e.g., 5th of every month',
                            'required' => true,
                        ],
                    ]
                ],
                'sample_output' => null,
                'is_active' => true,
                'created_by' => $admin->id,
            ],

            [
                'name' => 'Promissory Note',
                'description' => 'Written promise to pay a specific amount',
                'category' => 'Contracts',
                'form_fields' => [
                    'fields' => [
                        [
                            'id' => 'borrower_name',
                            'type' => 'text',
                            'label' => 'Borrower Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'borrower_address',
                            'type' => 'textarea',
                            'label' => 'Borrower Address',
                            'required' => true,
                            'rows' => 2
                        ],
                        [
                            'id' => 'lender_name',
                            'type' => 'text',
                            'label' => 'Lender Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'lender_address',
                            'type' => 'textarea',
                            'label' => 'Lender Address',
                            'required' => true,
                            'rows' => 2
                        ],
                        [
                            'id' => 'loan_amount',
                            'type' => 'number',
                            'label' => 'Loan Amount (PHP)',
                            'required' => true,
                        ],
                        [
                            'id' => 'interest_rate',
                            'type' => 'text',
                            'label' => 'Interest Rate',
                            'placeholder' => 'e.g., 5% per annum',
                            'required' => false,
                        ],
                        [
                            'id' => 'payment_terms',
                            'type' => 'textarea',
                            'label' => 'Payment Terms',
                            'placeholder' => 'Describe how and when payment will be made',
                            'required' => true,
                            'rows' => 3
                        ],
                        [
                            'id' => 'due_date',
                            'type' => 'date',
                            'label' => 'Due Date',
                            'required' => true,
                        ],
                    ]
                ],
                'sample_output' => null,
                'is_active' => true,
                'created_by' => $admin->id,
            ],

            // DEEDS
            [
                'name' => 'Deed of Absolute Sale (Personal Property)',
                'description' => 'Transfer of ownership of personal property',
                'category' => 'Deeds',
                'form_fields' => [
                    'fields' => [
                        [
                            'id' => 'seller_name',
                            'type' => 'text',
                            'label' => 'Seller Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'seller_address',
                            'type' => 'textarea',
                            'label' => 'Seller Address',
                            'required' => true,
                            'rows' => 2
                        ],
                        [
                            'id' => 'buyer_name',
                            'type' => 'text',
                            'label' => 'Buyer Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'buyer_address',
                            'type' => 'textarea',
                            'label' => 'Buyer Address',
                            'required' => true,
                            'rows' => 2
                        ],
                        [
                            'id' => 'property_description',
                            'type' => 'textarea',
                            'label' => 'Property Description',
                            'placeholder' => 'Detailed description of the property being sold',
                            'required' => true,
                            'rows' => 4
                        ],
                        [
                            'id' => 'purchase_price',
                            'type' => 'number',
                            'label' => 'Purchase Price (PHP)',
                            'required' => true,
                        ],
                        [
                            'id' => 'payment_terms',
                            'type' => 'select',
                            'label' => 'Payment Terms',
                            'required' => true,
                            'options' => ['Full Payment', 'Installment', 'Other']
                        ],
                    ]
                ],
                'sample_output' => null,
                'is_active' => true,
                'created_by' => $admin->id,
            ],

            // AUTHORIZATIONS
            [
                'name' => 'Special Power of Attorney',
                'description' => 'Authorization to act on behalf of another person',
                'category' => 'Authorizations',
                'form_fields' => [
                    'fields' => [
                        [
                            'id' => 'principal_name',
                            'type' => 'text',
                            'label' => 'Principal Name (Person giving authority)',
                            'required' => true,
                        ],
                        [
                            'id' => 'principal_address',
                            'type' => 'textarea',
                            'label' => 'Principal Address',
                            'required' => true,
                            'rows' => 2
                        ],
                        [
                            'id' => 'attorney_name',
                            'type' => 'text',
                            'label' => 'Attorney-in-Fact Name (Person receiving authority)',
                            'required' => true,
                        ],
                        [
                            'id' => 'attorney_address',
                            'type' => 'textarea',
                            'label' => 'Attorney-in-Fact Address',
                            'required' => true,
                            'rows' => 2
                        ],
                        [
                            'id' => 'powers_granted',
                            'type' => 'textarea',
                            'label' => 'Powers Granted',
                            'placeholder' => 'Describe specific powers being granted',
                            'required' => true,
                            'rows' => 5
                        ],
                        [
                            'id' => 'purpose',
                            'type' => 'text',
                            'label' => 'Purpose',
                            'placeholder' => 'e.g., To sell property, to claim documents',
                            'required' => true,
                        ],
                        [
                            'id' => 'validity_period',
                            'type' => 'text',
                            'label' => 'Validity Period',
                            'placeholder' => 'e.g., Until revoked, 1 year',
                            'required' => false,
                        ],
                    ]
                ],
                'sample_output' => null,
                'is_active' => true,
                'created_by' => $admin->id,
            ],

            [
                'name' => 'Waiver',
                'description' => 'Voluntary relinquishment of a right or claim',
                'category' => 'Authorizations',
                'form_fields' => [
                    'fields' => [
                        [
                            'id' => 'full_name',
                            'type' => 'text',
                            'label' => 'Full Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'address',
                            'type' => 'textarea',
                            'label' => 'Complete Address',
                            'required' => true,
                            'rows' => 2
                        ],
                        [
                            'id' => 'right_waived',
                            'type' => 'textarea',
                            'label' => 'Right/Claim Being Waived',
                            'placeholder' => 'Describe what right or claim is being waived',
                            'required' => true,
                            'rows' => 4
                        ],
                        [
                            'id' => 'in_favor_of',
                            'type' => 'text',
                            'label' => 'In Favor Of',
                            'placeholder' => 'Person or entity benefiting from waiver',
                            'required' => false,
                        ],
                        [
                            'id' => 'reason',
                            'type' => 'textarea',
                            'label' => 'Reason for Waiver',
                            'required' => false,
                            'rows' => 3
                        ],
                    ]
                ],
                'sample_output' => null,
                'is_active' => true,
                'created_by' => $admin->id,
            ],

            // DEMAND LETTERS
            [
                'name' => 'Demand Letter (Payment)',
                'description' => 'Formal demand for payment of debt',
                'category' => 'Demand Letters',
                'form_fields' => [
                    'fields' => [
                        [
                            'id' => 'creditor_name',
                            'type' => 'text',
                            'label' => 'Creditor Name (Your Name)',
                            'required' => true,
                        ],
                        [
                            'id' => 'creditor_address',
                            'type' => 'textarea',
                            'label' => 'Creditor Address',
                            'required' => true,
                            'rows' => 2
                        ],
                        [
                            'id' => 'debtor_name',
                            'type' => 'text',
                            'label' => 'Debtor Name',
                            'required' => true,
                        ],
                        [
                            'id' => 'debtor_address',
                            'type' => 'textarea',
                            'label' => 'Debtor Address',
                            'required' => true,
                            'rows' => 2
                        ],
                        [
                            'id' => 'amount_owed',
                            'type' => 'number',
                            'label' => 'Amount Owed (PHP)',
                            'required' => true,
                        ],
                        [
                            'id' => 'debt_details',
                            'type' => 'textarea',
                            'label' => 'Details of Debt',
                            'placeholder' => 'Describe the nature and origin of the debt',
                            'required' => true,
                            'rows' => 4
                        ],
                        [
                            'id' => 'payment_deadline',
                            'type' => 'text',
                            'label' => 'Payment Deadline',
                            'placeholder' => 'e.g., 5 days from receipt',
                            'required' => true,
                        ],
                    ]
                ],
                'sample_output' => null,
                'is_active' => true,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($templates as $template) {
            DocumentTemplate::create($template);
        }

        $this->command->info('Created ' . count($templates) . ' document templates');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        $specializations = [
            [
                'name' => 'Family Law',
                'description' => 'Marriage, divorce, child custody, adoption, and domestic relations',
                'icon' => '⚖️',
                'children' => [
                    'Annulment / Nullity of Marriage',
                    'Legal Separation',
                    'Child Custody',
                    'Child Support',
                    'Adoption',
                    'Domestic Violence (VAWC)',
                    'Property Settlement (Conjugal)',
                    'Guardianship',
                ],
            ],
            [
                'name' => 'Criminal Law',
                'description' => 'Defense and prosecution of criminal offenses',
                'icon' => '⚖️',
                'children' => [
                    'Estafa / Fraud',
                    'Cybercrime',
                    'Drugs Cases',
                    'Theft / Robbery',
                    'Assault / Physical Injury',
                    'Murder / Homicide',
                    'Libel / Cyber Libel',
                    'Bail & Defense',
                    'Police Complaints',
                ],
            ],
            [
                'name' => 'Corporate Law',
                'description' => 'Business formation, contracts, mergers, and corporate governance',
                'icon' => '💼',
                'children' => [
                    'Business Registration (SEC/DTI)',
                    'Contracts & Agreements',
                    'Corporate Compliance',
                    'Partnerships & Shareholders',
                    'Mergers & Acquisitions',
                    'Startup Legal Setup',
                    'Terms & Privacy Policy',
                    'Corporate Disputes',
                ],
            ],
            [
                'name' => 'Labor Law',
                'description' => 'Employment disputes, workplace rights, and labor relations',
                'icon' => '👷',
                'children' => [
                    'Illegal Dismissal',
                    'DOLE Complaints',
                    'Final Pay / Backpay',
                    'Contract Review',
                    'Employer Compliance',
                    'NLRC Cases',
                    'OFW Labor Issues',
                    'Workplace Harassment',
                ],
            ],
            [
                'name' => 'Real Estate Law',
                'description' => 'Property transactions, land titles, and real estate disputes',
                'icon' => '🏠',
                'children' => [
                    'Land Title Issues',
                    'Transfer of Title',
                    'Deed of Sale',
                    'Lease Contracts',
                    'Property Disputes',
                    'Ejectment Cases',
                    'Property Foreclosure',
                    'HOA Disputes',
                    'Developer Issues',
                ],
            ],
            [
                'name' => 'Civil Law',
                'description' => 'Personal injury, contracts, and civil disputes',
                'icon' => '📋',
                'children' => [
                    'Breach of Contract',
                    'Damages Claims',
                    'Small Claims',
                    'Debt Recovery',
                    'Personal Disputes',
                    'Demand Letters',
                    'Notarization',
                    'Affidavits',
                ],
            ],
            [
                'name' => 'Tax Law',
                'description' => 'Tax planning, compliance, and tax disputes',
                'icon' => '💰',
                'children' => [
                    'BIR Cases',
                    'Tax Compliance',
                    'Business Tax Filing',
                    'Estate Tax',
                    'VAT Issues',
                    'Tax Mapping',
                    'Tax Penalties',
                    'Corporate Tax Structuring',
                ],
            ],
            [
                'name' => 'Immigration Law',
                'description' => 'Visa applications, citizenship, and immigration matters',
                'icon' => '🌍',
                'children' => [
                    'Visa Application',
                    'Deportation Defense',
                    'Work Permits',
                    'PR / Residency',
                    'Citizenship',
                    'Marriage Visa',
                    'Student Visa',
                    'Overseas Migration',
                ],
            ],
            [
                'name' => 'Intellectual Property',
                'description' => 'Patents, trademarks, copyrights, and IP protection',
                'icon' => '💡',
                'children' => [
                    'Trademark Registration',
                    'Copyright',
                    'Patent',
                    'Business Name Protection',
                    'IP Disputes',
                    'NFT/Digital Rights',
                    'Brand Protection',
                    'Licensing',
                ],
            ],
            [
                'name' => 'Banking & Finance',
                'description' => 'Financial regulations, banking law, and securities',
                'icon' => '🏦',
                'children' => [
                    'Loan Disputes',
                    'Credit Card Cases',
                    'Collection Harassment',
                    'Bank Foreclosure',
                    'Bank Fraud',
                    'Investment Scams',
                    'SEC Investment Cases',
                    'Financing Contracts',
                ],
            ],
        ];

        foreach ($specializations as $spec) {
            $parent = Specialization::create([
                'name' => $spec['name'],
                'slug' => Str::slug($spec['name']),
                'description' => $spec['description'],
                'icon' => $spec['icon'],
                'is_parent' => true,
            ]);

            // Create sub-specializations
            if (isset($spec['children'])) {
                foreach ($spec['children'] as $child) {
                    Specialization::create([
                        'name' => $child,
                        'slug' => Str::slug($child),
                        'description' => $child . ' under ' . $spec['name'],
                        'icon' => $spec['icon'],
                        'parent_id' => $parent->id,
                        'is_parent' => false,
                    ]);
                }
            }
        }

        $this->command->info('Created ' . Specialization::count() . ' specializations (parents and children)');
    }
}

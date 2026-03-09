<?php

namespace Database\Seeders;

use App\Models\ContentCategory;
use Illuminate\Database\Seeder;

class ContentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Legal Guide Categories
            [
                'name' => 'Family Law',
                'type' => 'legal_guide',
                'description' => 'Legal guides related to family matters, marriage, divorce, and child custody',
                'order' => 1,
            ],
            [
                'name' => 'Criminal Law',
                'type' => 'legal_guide',
                'description' => 'Guides on criminal offenses, rights of the accused, and criminal procedures',
                'order' => 2,
            ],
            [
                'name' => 'Labor Law',
                'type' => 'legal_guide',
                'description' => 'Employment rights, workplace issues, and labor regulations',
                'order' => 3,
            ],
            [
                'name' => 'Business Law',
                'type' => 'legal_guide',
                'description' => 'Corporate law, business registration, and commercial transactions',
                'order' => 4,
            ],
            [
                'name' => 'Property Law',
                'type' => 'legal_guide',
                'description' => 'Real estate, land titles, and property rights',
                'order' => 5,
            ],
            [
                'name' => 'Civil Law',
                'type' => 'legal_guide',
                'description' => 'General civil matters, obligations, and contracts',
                'order' => 6,
            ],
            
            // Blog Categories
            [
                'name' => 'Legal Insights',
                'type' => 'blog',
                'description' => 'Expert analysis and commentary on legal developments',
                'order' => 1,
            ],
            [
                'name' => 'Legal Opinions',
                'type' => 'blog',
                'description' => 'Professional opinions on current legal issues',
                'order' => 2,
            ],
            [
                'name' => 'Legal Tips',
                'type' => 'blog',
                'description' => 'Practical advice and tips for legal matters',
                'order' => 3,
            ],
            [
                'name' => 'Case Studies',
                'type' => 'blog',
                'description' => 'Analysis of notable legal cases and their implications',
                'order' => 4,
            ],
            [
                'name' => 'Legal Updates',
                'type' => 'blog',
                'description' => 'Latest changes in laws and regulations',
                'order' => 5,
            ],
            
            // Downloadable Categories
            [
                'name' => 'Legal Contracts',
                'type' => 'downloadable',
                'description' => 'Sample contracts and agreements',
                'order' => 1,
            ],
            [
                'name' => 'Legal Forms',
                'type' => 'downloadable',
                'description' => 'Official forms and applications',
                'order' => 2,
            ],
            [
                'name' => 'Document Templates',
                'type' => 'downloadable',
                'description' => 'Ready-to-use document templates',
                'order' => 3,
            ],
            [
                'name' => 'Legal Guides',
                'type' => 'downloadable',
                'description' => 'Comprehensive guides in PDF format',
                'order' => 4,
            ],
            [
                'name' => 'Checklists',
                'type' => 'downloadable',
                'description' => 'Legal checklists and procedures',
                'order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            ContentCategory::create($category);
        }

        $this->command->info('Content categories seeded successfully!');
    }
}

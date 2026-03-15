<?php

namespace App\Livewire\Resources;

use Livewire\Component;

class WebinarsIndex extends Component
{
    public $webinars;

    public function mount()
    {
        // Mock data since there's no Database model yet
        $this->webinars = collect([
            [
                'slug' => 'navigating-corporate-tax-2026',
                'title' => 'Navigating Corporate Tax Laws in 2026',
                'date' => '2026-04-15 14:00:00',
                'status' => 'Upcoming',
                'speakers' => 'Atty. James Santos, Atty. Maria Clara',
                'description' => 'A comprehensive guide on the upcoming corporate tax changes affecting SMEs in the Philippines for the fiscal year 2026. Learn about new deductions, compliance requirements, and strategies for optimal tax planning.',
                'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080',
            ],
            [
                'slug' => 'labor-rights-remote-work',
                'title' => 'Labor Rights in the Remote Work Era',
                'date' => now()->addDays(2)->format('Y-m-d 10:00:00'),
                'status' => 'Ongoing',
                'speakers' => 'Atty. Roberto Dela Cruz',
                'description' => 'Explore the legal boundaries of the telecommuting act. Understand employee rights regarding equipment, working hours, and dispute resolution for work-from-home setups.',
                'image' => 'https://images.unsplash.com/photo-1593642532842-98d0fd5ebe61?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080',
            ],
            [
                'slug' => 'intellectual-property-startups',
                'title' => 'Protecting Intellectual Property for Tech Startups',
                'date' => '2026-05-10 13:00:00',
                'status' => 'Upcoming',
                'speakers' => 'Atty. Sarah Villanueva',
                'description' => 'Essential legal strategies for tech founders. From patenting software algorithms to trademarking brand names, learn how to safeguard your most valuable business assets before launch.',
                'image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080',
            ],
            [
                'slug' => 'family-law-annulment-ph',
                'title' => 'Understanding the Annulment Process in the PH',
                'date' => '2026-05-20 09:00:00',
                'status' => 'Upcoming',
                'speakers' => 'Atty. Elena Garcia',
                'description' => 'An in-depth but accessible breakdown of the legal grounds, procedures, and timelines involved in filing for annulment in the Philippines.',
                'image' => 'https://images.unsplash.com/photo-1521790797524-b2497295b8a0?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080',
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.resources.webinars-index')->layout('layouts.guest');
    }
}

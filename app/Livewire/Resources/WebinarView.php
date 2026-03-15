<?php

namespace App\Livewire\Resources;

use Livewire\Component;

class WebinarView extends Component
{
    public $slug;
    public $webinar;

    public $name;
    public $email;
    public $company;
    public $questions;
    public $registered = false;

    public function mount($slug)
    {
        $this->slug = $slug;
        
        // Mock data matching the index list
        $allWebinars = [
            'navigating-corporate-tax-2026' => [
                'slug' => 'navigating-corporate-tax-2026',
                'title' => 'Navigating Corporate Tax Laws in 2026',
                'date' => '2026-04-15 14:00:00',
                'duration' => '2 Hours',
                'platform' => 'Zoom Webinar',
                'status' => 'Upcoming',
                'speakers' => [
                    ['name' => 'Atty. James Santos', 'role' => 'Corporate Tax Specialist', 'image' => 'https://i.pravatar.cc/150?u=james'],
                    ['name' => 'Atty. Maria Clara', 'role' => 'Senior Partner, Finance', 'image' => 'https://i.pravatar.cc/150?u=maria'],
                ],
                'description' => 'A comprehensive guide on the upcoming corporate tax changes affecting SMEs in the Philippines for the fiscal year 2026. Learn about new deductions, compliance requirements, and strategies for optimal tax planning.',
                'agenda' => [
                    '14:00 - Introduction & Overview of changes',
                    '14:30 - Key deductions and compliance metrics',
                    '15:15 - Q&A Session with the Panel',
                ],
                'image' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080',
            ],
            'labor-rights-remote-work' => [
                'slug' => 'labor-rights-remote-work',
                'title' => 'Labor Rights in the Remote Work Era',
                'date' => now()->addDays(2)->format('Y-m-d 10:00:00'),
                'duration' => '1.5 Hours',
                'platform' => 'Google Meet',
                'status' => 'Ongoing',
                'speakers' => [
                    ['name' => 'Atty. Roberto Dela Cruz', 'role' => 'Labor Law Expert', 'image' => 'https://i.pravatar.cc/150?u=roberto'],
                ],
                'description' => 'Explore the legal boundaries of the telecommuting act. Understand employee rights regarding equipment, working hours, and dispute resolution for work-from-home setups.',
                'agenda' => [
                    '10:00 - The Telecommuting Act Refreshed',
                    '10:45 - Employer Liabilities vs Employee Rights',
                    '11:15 - Open Forum',
                ],
                'image' => 'https://images.unsplash.com/photo-1593642532842-98d0fd5ebe61?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080',
            ],
            // Missing slugs will default back to a template
        ];

        if (isset($allWebinars[$slug])) {
            $this->webinar = $allWebinars[$slug];
        } else {
            // Fallback generic data
            $this->webinar = [
                'slug' => $slug,
                'title' => 'Understanding ' . ucwords(str_replace('-', ' ', $slug)),
                'date' => now()->addDays(7)->format('Y-m-d 13:00:00'),
                'duration' => '1 Hour',
                'platform' => 'Zoom Webinar',
                'status' => 'Upcoming',
                'speakers' => [
                    ['name' => 'Atty. Legal Expert', 'role' => 'Senior Associate', 'image' => 'https://i.pravatar.cc/150?u=expert'],
                ],
                'description' => 'Join us for an exclusive insight into the legal fundamentals surrounding this topic. Our leading expert will break down everything you need to know to stay compliant.',
                'agenda' => [
                    '13:00 - Welcome & Introduction',
                    '13:20 - Core Legal Concepts',
                    '13:45 - Q&A Session',
                ],
                'image' => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080',
            ];
        }
    }

    public function register()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // Proceed to register logic (mocked)
        $this->registered = true;
    }

    public function render()
    {
        return view('livewire.resources.webinar-view')->layout('layouts.guest');
    }
}

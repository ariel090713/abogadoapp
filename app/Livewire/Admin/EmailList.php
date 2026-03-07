<?php

namespace App\Livewire\Admin;

use App\Models\NewsletterSubscriber;
use Livewire\Component;
use Livewire\WithPagination;

class EmailList extends Component
{
    use WithPagination;

    public $email = '';
    public $search = '';
    public $showAddModal = false;

    protected $rules = [
        'email' => 'required|email|unique:newsletter_subscribers,email',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function addSubscriber()
    {
        $this->validate();

        NewsletterSubscriber::create([
            'email' => $this->email,
            'is_subscribed' => true,
        ]);

        session()->flash('success', 'Email added successfully!');
        $this->reset(['email', 'showAddModal']);
    }

    public function deleteSubscriber($id)
    {
        NewsletterSubscriber::findOrFail($id)->delete();
        session()->flash('success', 'Email deleted successfully!');
    }

    public function toggleSubscription($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->update(['is_subscribed' => !$subscriber->is_subscribed]);
        session()->flash('success', 'Subscription status updated!');
    }

    public function render()
    {
        $subscribers = NewsletterSubscriber::query()
            ->when($this->search, fn($q) => $q->where('email', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.email-list', [
            'subscribers' => $subscribers,
        ])->layout('layouts.dashboard', ['title' => 'Email List']);
    }
}

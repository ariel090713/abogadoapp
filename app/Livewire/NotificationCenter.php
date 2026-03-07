<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationCenter extends Component
{
    public $isOpen = false;
    public $notifications = [];
    public $unreadCount = 0;
    public $filter = 'all'; // all, unread, read

    protected $listeners = ['refreshNotifications' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $query = auth()->user()->notifications();
        
        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($this->filter === 'read') {
            $query->whereNotNull('read_at');
        }
        
        $this->notifications = $query->latest()->take(20)->get();
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
    }

    public function toggleSidebar()
    {
        $this->isOpen = !$this->isOpen;
        
        if ($this->isOpen) {
            $this->loadNotifications();
        }
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadNotifications();
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->loadNotifications();
    }

    public function deleteNotification($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->delete();
            $this->loadNotifications();
        }
    }

    public function navigateTo($notificationId, $url)
    {
        // Mark as read when clicked
        $this->markAsRead($notificationId);
        
        // Close sidebar
        $this->isOpen = false;
        
        // Navigate to URL using Livewire redirect
        $this->redirect($url, navigate: true);
    }

    public function getNotificationIcon($type)
    {
        return match($type) {
            'payment_successful', 'payment_received' => '💰',
            'payment_failed' => '❌',
            'consultation_scheduled' => '📅',
            'consultation_reminder' => '⏰',
            'consultation_starting' => '🔔',
            'consultation_completed' => '✅',
            'consultation_cancelled' => '❌',
            'document_uploaded' => '📄',
            'document_deleted' => '🗑️',
            'document_request_received' => '📝',
            'document_work_started' => '🚀',
            'document_completed' => '✅',
            'document_revision_requested' => '🔄',
            'document_revision_started' => '🔧',
            'follow_up_requested' => '💬',
            'follow_up_accepted' => '✅',
            'follow_up_declined' => '❌',
            'service_offered' => '🎯',
            'service_accepted' => '✅',
            'service_declined' => '❌',
            default => '🔵',
        };
    }

    public function render()
    {
        return view('livewire.notification-center');
    }
}

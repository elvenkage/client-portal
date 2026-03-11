<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;

class NotificationDropdown extends Component
{
    public $isOpen = false;

    public function toggleDropdown()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function closeDropdown()
    {
        $this->isOpen = false;
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', auth()->id())
            ->first();

        if ($notification) {
            $notification->update(['is_read' => true]);
        }
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function render()
    {
        $userId = auth()->id();

        return view('livewire.notification-dropdown', [
            'notifications' => Notification::where('user_id', $userId)
                ->with('project')
                ->latest('created_at')
                ->limit(10)
                ->get(),
            'unreadCount' => Notification::where('user_id', $userId)
                ->where('is_read', false)
                ->count(),
        ]);
    }
}

<?php

namespace App\Livewire\Clients;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ClientsIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $clients = User::where('role', 'client')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->withCount([
                'projects as projects_count' => function ($query) {
                    // Assuming relationships are set correctly in User model
                    // Project count client is a member of
                },
                'assignedTasks as tasks_count'
            ])
            ->latest()
            ->paginate(15);

        return view('livewire.clients.clients-index', [
            'clients' => $clients,
        ]);
    }
}

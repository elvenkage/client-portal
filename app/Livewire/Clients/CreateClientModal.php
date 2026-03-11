<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Client;

class CreateClientModal extends Component
{
    public bool $isOpen = false;

    // Form fields
    public string $name = '';
    public string $company_name = '';
    public string $email = '';
    public string $phone = '';
    public string $notes = '';

    #[On('openCreateClientModal')]
    public function openModal(): void
    {
        $this->resetForm();
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->resetValidation();
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->name = '';
        $this->company_name = '';
        $this->email = '';
        $this->phone = '';
        $this->notes = '';
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        Client::create($validated);

        $this->closeModal();
        $this->dispatch('client-created');
    }

    public function render()
    {
        return view('livewire.clients.create-client-modal');
    }
}

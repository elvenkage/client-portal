<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateClientModal extends Component
{
    public bool $isOpen = false;

    // Form fields
    public string $name = '';
    public string $email = '';
    public string $password = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|string|max:255|unique:users,email',
        'password' => 'required|string|min:6',
    ];

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
        $this->email = '';
        $this->password = '';
    }

    public function createClient(): void
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email ?: null,
            'password' => Hash::make($this->password),
            'role' => 'client',
        ]);

        $this->closeModal();
        $this->dispatch('client-created');
    }

    public function render()
    {
        return view('livewire.clients.create-client-modal');
    }
}

<?php

namespace App\Livewire\Projects;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;

class CreateProjectModal extends Component
{
    public bool $isOpen = false;

    // Form fields
    public string $name = '';
    public string $client_user_id = '';
    public string $project_manager_id = '';
    public string $start_date = '';
    public string $deadline = '';
    public string $description = '';

    #[On('openCreateProjectModal')]
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
        $this->client_user_id = '';
        $this->project_manager_id = '';
        $this->start_date = '';
        $this->deadline = '';
        $this->description = '';
    }

    public function save(ProjectService $projectService): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'client_user_id' => 'nullable|exists:users,id',
            'project_manager_id' => 'nullable|exists:users,id',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['client_user_id'] = $validated['client_user_id'] ?: null;
        $validated['project_manager_id'] = $validated['project_manager_id'] ?: null;

        // Apply default values per rules
        $validated['status'] = 'planning';
        $validated['visibility'] = 'draft';
        $validated['progress'] = 0;
        $validated['show_team_to_client'] = false;

        $projectService->createProject($validated);

        $this->closeModal();
        $this->dispatch('projectCreated');
    }

    public function render()
    {
        return view('livewire.projects.create-project-modal', [
            'clients' => User::where('role', 'client')->orderBy('name')->get(),
            'managers' => User::whereIn('role', ['admin', 'project_manager'])
                ->orderBy('name')
                ->get(),
        ]);
    }
}

<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class UserManager extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $isConfirmingDelete = false;

    // Form fields
    public $userId = null;
    public $name = '';
    public $email = '';
    public $role = 'team_member';

    public $search = '';
    public $roleFilter = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->userId),
            ],
            'role' => 'required|in:admin,project_manager,team_member,client',
        ];
    }

    public function mount()
    {
        // Only staff can access this panel
        abort_if(auth()->user()->isClient(), 403, 'Access restricted to staff.');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isConfirmingDelete = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['userId', 'name', 'email', 'role']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);

        // Prevent editing super_admin if not super_admin (basic safety)
        if ($user->role === 'super_admin' && auth()->user()->role !== 'super_admin') {
            session()->flash('error', 'You cannot edit a super admin account.');
            return;
        }

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;

        $this->isModalOpen = true;
    }

    public function saveUser()
    {
        $this->validate();

        if ($this->userId) {
            // Update existing user
            $user = User::findOrFail($this->userId);

            if ($user->role === 'super_admin' && auth()->user()->role !== 'super_admin') {
                session()->flash('error', 'You cannot edit a super admin account.');
                $this->closeModal();
                return;
            }

            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ]);

            session()->flash('success', 'User updated successfully.');
        } else {
            // Create new user
            $password = Str::random(16);

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($password),
                'role' => $this->role,
            ]);

            // Send standard Laravel password reset link as an invitation
            $token = Password::broker()->createToken($user);

            // This relies on the standard ResetPassword notification.
            // When clients click it, it acts as a "Set Password" flow.
            $user->sendPasswordResetNotification($token);

            session()->flash('success', 'User created and invitation sent.');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        if ($user->role === 'super_admin') {
            session()->flash('error', 'You cannot delete a super admin account.');
            return;
        }

        $this->userId = $user->id;
        $this->isConfirmingDelete = true;
    }

    public function deleteUser()
    {
        if ($this->userId) {
            $user = User::findOrFail($this->userId);

            if ($user->id !== auth()->id() && $user->role !== 'super_admin') {
                $user->delete();
                session()->flash('success', 'User deleted successfully.');
            }
        }

        $this->closeModal();
    }

    public function render()
    {
        $usersQuery = User::query();

        if ($this->search) {
            $usersQuery->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $usersQuery->where('role', $this->roleFilter);
        }

        $users = $usersQuery->latest()->paginate(10);

        return view('livewire.users.user-manager', [
            'users' => $users,
        ]);
    }
}

<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UpdateProfile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $avatar;

    public function mount()
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;
    }

    public function updateProfile()
    {
        $user = auth()->user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'avatar' => 'nullable|image|max:2048', // 2MB Max
        ]);

        if ($this->avatar) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $this->avatar->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();

        $this->dispatch('profile-updated');
    }

    public function render()
    {
        return view('livewire.settings.update-profile');
    }
}

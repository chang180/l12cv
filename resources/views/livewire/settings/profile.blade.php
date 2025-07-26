<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $avatar;
    public $tempAvatar;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'tempAvatar' => $this->tempAvatar ? ['image', 'max:1024'] : ['nullable'],
        ]);

        // 處理頭像上傳
        if ($this->tempAvatar) {
            // 刪除舊頭像
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }

            // 儲存新頭像
            $avatarPath = $this->tempAvatar->store('avatars', 'public');
            $user->avatar = $avatarPath;
    }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
        $this->reset('tempAvatar');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * 刪除用戶頭像
     */
    public function deleteAvatar(): void
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::delete($user->avatar);
            $user->avatar = null;
            $user->save();

            $this->dispatch('profile-updated', name: $user->name);
        }
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout heading="Profile" subheading="Update your profile information">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <!-- 頭像上傳區域 -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ __('Profile Picture') }}
                </label>

                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        <div class="relative">
                            @if ($tempAvatar)
                                <img class="h-24 w-24 rounded-full object-cover" src="{{ $tempAvatar->temporaryUrl() }}" alt="{{ auth()->user()->name }}">
                            @elseif (auth()->user()->avatar)
                                <img class="h-24 w-24 rounded-full object-cover" src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                            @else
                                <div class="h-24 w-24 rounded-full bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center">
                                    <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </span>
                    </div>
                @endif

                            @if (auth()->user()->avatar || $tempAvatar)
                                <button
                                    type="button"
                                    wire:click="deleteAvatar"
                                    class="absolute -top-2 -right-2 rounded-full bg-red-500 p-1 text-white hover:bg-red-600 focus:outline-none"
                                    title="Remove avatar"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            @endif
            </div>
                </div>

                    <div class="flex-1">
                        <input
                            type="file"
                            wire:model="tempAvatar"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:text-gray-400 dark:file:bg-primary-900/50 dark:file:text-primary-400"
                            accept="image/*"
                        />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG, or GIF. Max 1MB.</p>

                        @error('tempAvatar')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
            </div>
            </div>

            <flux:input wire:model="name" label="{{ __('Name') }}" type="text" name="name" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" label="{{ __('Email') }}" type="email" name="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <p class="mt-2 text-sm text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button
                                wire:click.prevent="resendVerificationNotification"
                                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-sm font-medium text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>

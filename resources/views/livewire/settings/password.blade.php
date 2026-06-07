<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
            'password_set_at' => now(),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout heading="密碼設定" subheading="設定一組安全密碼，讓您在未使用 Google 時仍可登入">
        <form wire:submit="updatePassword" class="mt-6 space-y-6">
            <input
                type="text"
                name="username"
                value="{{ auth()->user()->email }}"
                autocomplete="username"
                tabindex="-1"
                aria-hidden="true"
                class="sr-only"
                readonly
            />

            <flux:input
                wire:model="current_password"
                id="update_password_current_passwordpassword"
                label="{{ __('目前密碼') }}"
                type="password"
                name="current_password"
                required
                autocomplete="current-password"
            />
            <flux:input
                wire:model="password"
                id="update_password_password"
                label="{{ __('新密碼') }}"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />
            <flux:input
                wire:model="password_confirmation"
                id="update_password_password_confirmation"
                label="{{ __('確認新密碼') }}"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('儲存') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('已儲存。') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>

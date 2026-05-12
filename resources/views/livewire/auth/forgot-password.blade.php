<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('如果帳號存在，系統會寄出密碼重設連結。'));
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="忘記密碼" description="輸入電子郵件以接收密碼重設連結" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- 電子郵件 -->
        <flux:input
            wire:model="email"
            label="{{ __('電子郵件地址') }}"
            type="email"
            name="email"
            required
            autofocus
            placeholder="email@example.com"
        />

        <flux:button variant="primary" type="submit" class="w-full">{{ __('寄送密碼重設連結') }}</flux:button>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-400">
        或返回
        <x-text-link href="{{ route('login') }}">登入頁</x-text-link>
    </div>
</div>

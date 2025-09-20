<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth.split')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('resume.dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="flex flex-col gap-8">
    <!-- Header -->
    <div class="text-center space-y-4">
        <div class="flex justify-center">
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full blur opacity-75 animate-pulse"></div>
                <div class="relative p-4 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full">
                    <i class="fas fa-sign-in-alt text-white text-2xl"></i>
                </div>
            </div>
        </div>
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-400 bg-clip-text text-transparent mb-2">
                歡迎回來
            </h1>
            <p class="text-slate-600 dark:text-slate-400">請登入您的帳戶以繼續</p>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                <i class="fas fa-envelope mr-2 text-blue-500"></i>
                {{ __('電子郵件地址') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-at text-slate-400"></i>
                </div>
                <flux:input
                    wire:model="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="請輸入您的電子郵件"
                    class="w-full pl-10"
                />
            </div>
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                <i class="fas fa-lock mr-2 text-blue-500"></i>
                {{ __('密碼') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-key text-slate-400"></i>
                </div>
                <flux:input
                    wire:model="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="請輸入您的密碼"
                    class="w-full pl-10"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300" onclick="togglePassword()">
                        <i class="fas fa-eye" id="password-toggle"></i>
                    </button>
                </div>
            </div>
            
            @if (Route::has('password.request'))
                <div class="text-right">
                    <x-text-link href="{{ route('password.request') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                        <i class="fas fa-question-circle mr-1"></i>
                        {{ __('忘記密碼？') }}
                    </x-text-link>
                </div>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <flux:checkbox wire:model="remember" class="mr-2" />
                <span class="text-sm text-slate-700 dark:text-slate-300">
                    <i class="fas fa-user-check mr-1 text-green-500"></i>
                    {{ __('記住我') }}
                </span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="relative">
            <flux:button 
                variant="primary" 
                type="submit" 
                class="w-full py-3 text-lg font-semibold bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 transform hover:-translate-y-0.5 transition-all duration-300 shadow-lg hover:shadow-xl"
                wire:loading.attr="disabled"
                wire:target="login"
            >
                <span wire:loading.remove wire:target="login" class="flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    {{ __('登入') }}
                </span>
                <span wire:loading wire:target="login" class="flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    登入中...
                </span>
            </flux:button>
        </div>
    </form>

    <!-- Divider -->
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400">
                <i class="fas fa-user-plus mr-1"></i>
                新用戶
            </span>
        </div>
    </div>

    <!-- Sign up link -->
    <div class="text-center">
        <p class="text-sm text-slate-600 dark:text-slate-400">
            還沒有帳戶？
            <x-text-link href="{{ route('register') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                <i class="fas fa-user-plus mr-1"></i>
                立即註冊
            </x-text-link>
        </p>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.querySelector('input[type="password"]');
    const toggleIcon = document.getElementById('password-toggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>

<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth.split')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('resume.dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-8">
    <!-- Header -->
    <div class="text-center space-y-4">
        <div class="flex justify-center">
            <div class="relative">
                <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-blue-500 rounded-full blur opacity-75 animate-pulse"></div>
                <div class="relative p-4 bg-gradient-to-r from-green-600 to-blue-600 rounded-full">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
            </div>
        </div>
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-400 bg-clip-text text-transparent mb-2">
                建立新帳戶
            </h1>
            <p class="text-slate-600 dark:text-slate-400">請填寫以下資訊來建立您的帳戶</p>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                <i class="fas fa-user mr-2 text-blue-500"></i>
                {{ __('姓名') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-id-card text-slate-400"></i>
                </div>
                <flux:input
                    wire:model="name"
                    id="name"
                    type="text"
                    name="name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="請輸入您的姓名"
                    class="w-full pl-10"
                />
            </div>
        </div>

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
                    id="email"
                    type="email"
                    name="email"
                    required
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
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="請輸入密碼"
                    class="w-full pl-10"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password-toggle-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="space-y-2">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                <i class="fas fa-lock mr-2 text-blue-500"></i>
                {{ __('確認密碼') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-key text-slate-400"></i>
                </div>
                <flux:input
                    wire:model="password_confirmation"
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="請再次輸入密碼"
                    class="w-full pl-10"
                />
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye" id="password-toggle-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="relative">
            <flux:button 
                type="submit" 
                variant="primary" 
                class="w-full py-3 text-lg font-semibold bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 transform hover:-translate-y-0.5 transition-all duration-300 shadow-lg hover:shadow-xl"
                wire:loading.attr="disabled"
                wire:target="register"
            >
                <span wire:loading.remove wire:target="register" class="flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    {{ __('建立帳戶') }}
                </span>
                <span wire:loading wire:target="register" class="flex items-center justify-center">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    建立中...
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
                <i class="fas fa-sign-in-alt mr-1"></i>
                已有帳戶
            </span>
        </div>
    </div>

    <!-- Sign in link -->
    <div class="text-center">
        <p class="text-sm text-slate-600 dark:text-slate-400">
            已經有帳戶了？
            <x-text-link href="{{ route('login') }}" class="font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                <i class="fas fa-sign-in-alt mr-1"></i>
                立即登入
            </x-text-link>
        </p>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleIcon = document.getElementById(`password-toggle-${fieldId === 'password' ? '1' : '2'}`);
    
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

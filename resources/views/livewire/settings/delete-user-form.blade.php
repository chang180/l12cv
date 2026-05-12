<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('刪除帳號') }}</flux:heading>
        <flux:subheading>{{ __('刪除帳號後，所有履歷與作品資料都會一併移除。') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('刪除帳號') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('確定要刪除帳號嗎？') }}</flux:heading>

                <flux:subheading>
                    {{ __('帳號刪除後，所有資源與資料都會永久刪除。請輸入密碼確認。') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="password" id="password" label="{{ __('密碼') }}" type="password" name="password" />

            <div class="flex justify-end space-x-2">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('取消') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit">{{ __('刪除帳號') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>

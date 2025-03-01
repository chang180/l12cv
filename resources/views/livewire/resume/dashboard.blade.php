<?php
use function Livewire\Volt\{state, mount};
use App\Models\Resume;

state(['resume' => null, 'isPublic' => false]);

mount(function () {
    $this->resume = auth()->user()->resume;
    $this->isPublic = (int) $this->resume->is_public;
});

$edit = function () {
    return $this->redirect(route('resume.edit'), navigate: true);
};

$settings = function () {
    return $this->redirect(route('resume.settings'), navigate: true);
};

// @todo 修正第二次更新資料後，狀態會異常的問題
$updateVisibility = function ($value) {
    $value = (int) $value;
    $this->resume->update(['is_public' => $value]);
};

$create = function () {
    $resume = auth()
        ->user()
        ->resume()
        ->create([
            'title' => auth()->user()->name . ' 的履歷',
            'slug' => strtolower(str_replace(' ', '-', auth()->user()->name)),
            'summary' => '',
            'education' => [],
            'experience' => [],
            'is_public' => false,
        ]);

    return $this->redirect(route('resume.edit'), navigate: true);
};
?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('控制台') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- 履歷管理區塊 -->
            <x-card>
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('履歷管理') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('管理你的個人履歷，設定公開狀態與分享連結。') }}
                            </p>
                        </header>

                        <div class="mt-6 space-y-6">
                            @if ($resume)
                                <x-card>
                                    <div class="flex items-center justify-between" x-data="{
                                        resume: @js($resume),
                                        isPublic: Number(@js($resume->is_public)),
                                        slug: @js($resume->slug),
                                        title: @js($resume->title)
                                    }">
                                        <!-- 左側：標題和狀態 -->
                                        <div>
                                            <h3 class="text-lg font-medium" x-text="title"></h3>
                                            <p class="text-sm text-gray-500">
                                                <span x-text="isPublic == 1 ? '公開' : '未公開'"></span> ·
                                                <template x-if="isPublic == 1">
                                                    <a :href="`/@${slug}`"
                                                        class="text-primary-600 hover:text-primary-500"
                                                        x-text="`@${slug}`">
                                                    </a>
                                                </template>
                                                <template x-if="isPublic != 1">
                                                    <span class="text-gray-400" x-text="`@${slug}`"></span>
                                                </template>
                                            </p>
                                        </div>
                                        <!-- 中間：編輯按鈕和公開狀態切換 -->
                                        <div class="flex items-center gap-6">
                                            <flux:button wire:click="edit" icon="pencil" variant="primary">
                                                編輯
                                            </flux:button>
                                            <flux:radio.group x-model="isPublic" variant="segmented"
                                                wire:change="updateVisibility($event.target.value)"
                                                class="flex items-center">
                                                <flux:radio value="1" icon="eye">
                                                    公開
                                                </flux:radio>
                                                <flux:radio value="0" icon="eye-slash">
                                                    不公開
                                                </flux:radio>
                                            </flux:radio.group>
                                        </div>
                                        <!-- 右側：預覽按鈕 -->
                                        <template x-if="isPublic == 1">
                                            <flux:button href="/@" x-bind:href="`/@${slug}`" target="_blank"
                                                icon="eye" variant="ghost">
                                                預覽履歷
                                            </flux:button>
                                        </template>
                                    </div>
                                </x-card>
                            @else
                                <x-card>
                                    <div class="text-center">
                                        <h3 class="text-lg font-medium">還沒有履歷</h3>
                                        <p class="mt-1 text-sm text-gray-500">開始建立你的第一份履歷吧！</p>
                                        <div class="mt-4">
                                            <flux:button wire:click="create" icon="plus" variant="primary">
                                                建立履歷
                                            </flux:button>
                                        </div>
                                    </div>
                                </x-card>
                            @endif
                        </div>
                    </section>
                </div>
            </x-card>

            <!-- 個人資料設定區塊 -->
            <x-card>
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('個人資料') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('更新你的帳號資料與電子郵件。') }}
                            </p>
                        </header>
                        <div class="mt-6">
                            <flux:button href="{{ route('settings.profile') }}" variant="primary">
                                {{ __('編輯個人資料') }}
                            </flux:button>
                        </div>
                    </section>
                </div>
            </x-card>

            <!-- 密碼設定區塊 -->
            <x-card>
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('安全性') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('確保你的帳號使用夠強的密碼以保持安全。') }}
                            </p>
                        </header>
                        <div class="mt-6">
                            <flux:button href="{{ route('settings.password') }}" variant="primary">
                                {{ __('變更密碼') }}
                            </flux:button>
                        </div>
                    </section>
                </div>
            </x-card>

            <!-- 外觀設定區塊 -->
            <x-card>
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('外觀設定') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('自訂你的使用體驗，設定深色模式與其他顯示選項。') }}
                            </p>
                        </header>
                        <div class="mt-6">
                            <flux:button href="{{ route('settings.appearance') }}" variant="primary">
                                {{ __('調整外觀') }}
                            </flux:button>
                        </div>
                    </section>
                </div>
            </x-card>
        </div>
    </div>
</div>
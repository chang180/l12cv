<?php

use function Livewire\Volt\{state, mount, computed};
use App\Models\Resume;

state(['resume' => null]);

mount(function () {
    $this->resume = auth()->user()->resume;
});

$edit = function () {
    return $this->redirect(route('resume.edit'), navigate: true);
};

$settings = function () {
    return $this->redirect(route('resume.settings'), navigate: true);
};

$create = function () {
    // 建立基本履歷
    $resume = auth()->user()->resume()->create([
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
            {{ __('履歷管理') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="space-y-4">
                        @if($resume)
                            <x-filament::card>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-medium">{{ $resume->title }}</h3>
                                        <p class="text-sm text-gray-500">
                                            {{ $resume->is_public ? '公開' : '未公開' }} ·
                                            @if($resume->is_public)
                                                <a href="{{ route('resume.public', $resume->slug) }}" class="text-primary-600 hover:text-primary-500">
                                                    {{ '@' . $resume->slug }}
                                                </a>
                                            @else
                                                {{ '@' . $resume->slug }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <x-filament::button
                                            wire:click="$edit"
                                            icon="heroicon-o-pencil"
                                        >
                                            編輯
                                        </x-filament::button>
                                        <x-filament::button
                                            wire:click="$settings"
                                            icon="heroicon-o-cog"
                                            color="secondary"
                                        >
                                            設定
                                        </x-filament::button>
                                    </div>
                                </div>
                            </x-filament::card>
                        @else
                            <x-filament::card>
                                <div class="text-center">
                                    <h3 class="text-lg font-medium">還沒有履歷</h3>
                                    <p class="mt-1 text-sm text-gray-500">開始建立你的第一份履歷吧！</p>
                                    <div class="mt-4">
                                        <x-filament::button
                                            wire:click="$create"
                                            icon="heroicon-o-plus"
                                        >
                                            建立履歷
                                        </x-filament::button>
                                    </div>
                                </div>
                            </x-filament::card>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

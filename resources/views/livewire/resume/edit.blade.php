<?php

use function Livewire\Volt\{state, mount, computed};
use App\Models\Resume;

state([
    'resume' => null,
    'title' => '',
    'summary' => '',
    'education' => [],
    'experience' => [],
    'currentTab' => 'basic', // basic, education, experience
]);

mount(function () {
    $this->resume = auth()->user()->resume;
    if (!$this->resume) {
        return $this->redirect(route('resume.dashboard'), navigate: true);
    }

    $this->title = $this->resume->title;
    $this->summary = $this->resume->summary;
    $this->education = $this->resume->education ?? [];
    $this->experience = $this->resume->experience ?? [];
});

$updateBasicInfo = function() {
    $this->resume->update([
        'title' => $this->title,
        'summary' => $this->summary,
    ]);

    $this->dispatch('notify', [
        'message' => '基本資料已更新',
        'type' => 'success',
    ]);
};

$addEducation = function() {
    $this->education[] = [
        'school' => '',
        'degree' => '',
        'field' => '',
        'start_date' => '',
        'end_date' => '',
        'description' => '',
    ];
};

$removeEducation = function($index) {
    unset($this->education[$index]);
    $this->education = array_values($this->education);
};

$updateEducation = function() {
    $this->resume->update([
        'education' => $this->education,
    ]);

    $this->dispatch('notify', [
        'message' => '學歷資料已更新',
        'type' => 'success',
    ]);
};

$addExperience = function() {
    $this->experience[] = [
        'company' => '',
        'position' => '',
        'start_date' => '',
        'end_date' => '',
        'current' => false,
        'description' => '',
    ];
};

$removeExperience = function($index) {
    unset($this->experience[$index]);
    $this->experience = array_values($this->experience);
};

$updateExperience = function() {
    $this->resume->update([
        'experience' => $this->experience,
    ]);

    $this->dispatch('notify', [
        'message' => '工作經驗已更新',
        'type' => 'success',
    ]);
};

?>

<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('編輯履歷') }}
            </h2>
            <x-filament::button
                wire:click="$dispatch('save')"
                icon="heroicon-o-check"
                color="success"
            >
                儲存
            </x-filament::button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- 分頁標籤 -->
                    <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button
                                wire:click="$set('currentTab', 'basic')"
                                class="{{ $currentTab === 'basic' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            >
                                基本資料
                            </button>
                            <button
                                wire:click="$set('currentTab', 'education')"
                                class="{{ $currentTab === 'education' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            >
                                學歷
                            </button>
                            <button
                                wire:click="$set('currentTab', 'experience')"
                                class="{{ $currentTab === 'experience' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            >
                                工作經驗
                            </button>
                        </nav>
                    </div>

                    <!-- 基本資料表單 -->
                    <div x-show="$wire.currentTab === 'basic'">
                        <x-filament::card>
                            <form wire:submit="updateBasicInfo" class="space-y-6">
                                <div>
                                    <x-filament::input.wrapper>
                                        <x-filament::input.label for="title">標題</x-filament::input.label>
                                        <x-filament::input
                                            wire:model="title"
                                            id="title"
                                            type="text"
                                            required
                                        />
                                    </x-filament::input.wrapper>
                                </div>

                                <div>
                                    <x-filament::input.wrapper>
                                        <x-filament::input.label for="summary">簡介</x-filament::input.label>
                                        <x-filament::textarea
                                            wire:model="summary"
                                            id="summary"
                                            rows="4"
                                        />
                                    </x-filament::input.wrapper>
                                </div>

                                <div class="flex justify-end">
                                    <x-filament::button type="submit">
                                        更新基本資料
                                    </x-filament::button>
                                </div>
                            </form>
                        </x-filament::card>
                    </div>

                    <!-- 學歷表單 -->
                    <div x-show="$wire.currentTab === 'education'">
                        <div class="space-y-4">
                            @foreach($education as $index => $edu)
                                <x-filament::card>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-start">
                                            <h3 class="text-lg font-medium">學歷 #{{ $index + 1 }}</h3>
                                            <x-filament::button
                                                wire:click="removeEducation({{ $index }})"
                                                color="danger"
                                                size="sm"
                                                icon="heroicon-o-trash"
                                            >
                                                刪除
                                            </x-filament::button>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <x-filament::input.wrapper>
                                                <x-filament::input.label>學校</x-filament::input.label>
                                                <x-filament::input
                                                    wire:model="education.{{ $index }}.school"
                                                    type="text"
                                                />
                                            </x-filament::input.wrapper>

                                            <x-filament::input.wrapper>
                                                <x-filament::input.label>學位</x-filament::input.label>
                                                <x-filament::input
                                                    wire:model="education.{{ $index }}.degree"
                                                    type="text"
                                                />
                                            </x-filament::input.wrapper>

                                            <x-filament::input.wrapper>
                                                <x-filament::input.label>科系</x-filament::input.label>
                                                <x-filament::input
                                                    wire:model="education.{{ $index }}.field"
                                                    type="text"
                                                />
                                            </x-filament::input.wrapper>

                                            <div class="grid grid-cols-2 gap-4">
                                                <x-filament::input.wrapper>
                                                    <x-filament::input.label>開始日期</x-filament::input.label>
                                                    <x-filament::input
                                                        wire:model="education.{{ $index }}.start_date"
                                                        type="date"
                                                    />
                                                </x-filament::input.wrapper>

                                                <x-filament::input.wrapper>
                                                    <x-filament::input.label>結束日期</x-filament::input.label>
                                                    <x-filament::input
                                                        wire:model="education.{{ $index }}.end_date"
                                                        type="date"
                                                    />
                                                </x-filament::input.wrapper>
                                            </div>
                                        </div>

                                        <x-filament::input.wrapper>
                                            <x-filament::input.label>描述</x-filament::input.label>
                                            <x-filament::textarea
                                                wire:model="education.{{ $index }}.description"
                                                rows="3"
                                            />
                                        </x-filament::input.wrapper>
                                    </div>
                                </x-filament::card>
                            @endforeach

                            <div class="flex justify-between">
                                <x-filament::button
                                    wire:click="addEducation"
                                    icon="heroicon-o-plus"
                                >
                                    新增學歷
                                </x-filament::button>

                                <x-filament::button
                                    wire:click="updateEducation"
                                    color="success"
                                >
                                    儲存學歷資料
                                </x-filament::button>
                            </div>
                        </div>
                    </div>

                    <!-- 工作經驗表單 -->
                    <div x-show="$wire.currentTab === 'experience'" class="space-y-4">
                        @foreach($experience as $index => $exp)
                            <x-filament::card>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-lg font-medium">工作經驗 #{{ $index + 1 }}</h3>
                                        <x-filament::button
                                            wire:click="removeExperience({{ $index }})"
                                            color="danger"
                                            size="sm"
                                            icon="heroicon-o-trash"
                                        >
                                            刪除
                                        </x-filament::button>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <x-filament::input.wrapper>
                                            <x-filament::input.label>公司</x-filament::input.label>
                                            <x-filament::input
                                                wire:model="experience.{{ $index }}.company"
                                                type="text"
                                            />
                                        </x-filament::input.wrapper>

                                        <x-filament::input.wrapper>
                                            <x-filament::input.label>職位</x-filament::input.label>
                                            <x-filament::input
                                                wire:model="experience.{{ $index }}.position"
                                                type="text"
                                            />
                                        </x-filament::input.wrapper>

                                        <div class="grid grid-cols-2 gap-4">
                                            <x-filament::input.wrapper>
                                                <x-filament::input.label>開始日期</x-filament::input.label>
                                                <x-filament::input
                                                    wire:model="experience.{{ $index }}.start_date"
                                                    type="date"
                                                />
                                            </x-filament::input.wrapper>

                                            <x-filament::input.wrapper>
                                                <x-filament::input.label>結束日期</x-filament::input.label>
                                                <x-filament::input
                                                    wire:model="experience.{{ $index }}.end_date"
                                                    type="date"
                                                    :disabled="$experience[$index]['current'] ?? false"
                                                />
                                            </x-filament::input.wrapper>
                                        </div>

                                        <x-filament::input.wrapper>
                                            <label class="flex items-center">
                                                <x-filament::input
                                                    wire:model="experience.{{ $index }}.current"
                                                    type="checkbox"
                                                />
                                                <span class="ml-2">目前在職中</span>
                                            </label>
                                        </x-filament::input.wrapper>
                                    </div>

                                    <x-filament::input.wrapper>
                                        <x-filament::input.label>工作描述</x-filament::input.label>
                                        <x-filament::textarea
                                            wire:model="experience.{{ $index }}.description"
                                            rows="3"
                                        />
                                    </x-filament::input.wrapper>
                                </div>
                            </x-filament::card>
                        @endforeach

                        <div class="flex justify-between">
                            <x-filament::button
                                wire:click="addExperience"
                                icon="heroicon-o-plus"
                            >
                                新增工作經驗
                            </x-filament::button>

                            <x-filament::button
                                wire:click="updateExperience"
                                color="success"
                            >
                                儲存工作經驗
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

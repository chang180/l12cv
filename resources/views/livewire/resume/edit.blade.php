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

$updateBasicInfo = function () {
    $this->resume->update([
        'title' => $this->title,
        'summary' => $this->summary,
    ]);

    $this->dispatch('notify', [
        'message' => '基本資料已更新',
        'type' => 'success',
    ]);
};

$addEducation = function () {
    $this->education[] = [
        'school' => '',
        'degree' => '',
        'field' => '',
        'start_date' => '',
        'end_date' => '',
        'description' => '',
    ];
};

$removeEducation = function ($index) {
    unset($this->education[$index]);
    $this->education = array_values($this->education);
};

$updateEducation = function () {
    $this->resume->update([
        'education' => $this->education,
    ]);

    $this->dispatch('notify', [
        'message' => '學歷資料已更新',
        'type' => 'success',
    ]);
};

$addExperience = function () {
    $this->experience[] = [
        'company' => '',
        'position' => '',
        'start_date' => '',
        'end_date' => '',
        'current' => false,
        'description' => '',
    ];
};

$removeExperience = function ($index) {
    unset($this->experience[$index]);
    $this->experience = array_values($this->experience);
};

$updateExperience = function () {
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
            <flux:button wire:click="$dispatch('save')" variant="primary" class="hidden sm:flex">
                <flux:icon name="check-circle" class="w-5 h-5 mr-2" />
                儲存
            </flux:button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="p-6">
                    <!-- 分頁標籤 -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="flex -mb-px space-x-8">
                            <button wire:click="$set('currentTab', 'basic')"
                                class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $currentTab === 'basic' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                基本資料
                            </button>
                            <button wire:click="$set('currentTab', 'education')"
                                class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $currentTab === 'education' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                學歷
                            </button>
                            <button wire:click="$set('currentTab', 'experience')"
                                class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap {{ $currentTab === 'experience' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                工作經驗
                            </button>
                        </nav>
                    </div>
                    <!-- 基本資料表單 -->
                    <div x-show="$wire.currentTab === 'basic'">
                        <x-card>
                            <form wire:submit="updateBasicInfo" class="space-y-6">
                                <div>
                                    <flux:label for="title">標題</flux:label>
                                    <flux:input wire:model="title" id="title" type="text" required />
                                    @error('title')
                                        <flux:error :messages="$message" />
                                    @enderror
                                </div>
                                <div>
                                    <flux:label for="summary">簡介</flux:label>
                                    <flux:textarea wire:model="summary" id="summary" rows="4" />
                                    @error('summary')
                                        <flux:error :messages="$message" />
                                    @enderror
                                </div>
                                <div class="flex justify-end">
                                    <flux:button type="submit" variant="primary" class="w-full sm:w-auto">
                                        <flux:icon name="check-circle" class="w-5 h-5 mr-2" />
                                        更新基本資料
                                    </flux:button>
                                </div>
                            </form>
                        </x-card>
                    </div>

                    <!-- 學歷表單 -->
                    <div x-show="$wire.currentTab === 'education'">
                        <div class="space-y-4">
                            @foreach ($education as $index => $edu)
                                <x-card>
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-start flex-wrap gap-2">
                                            <h3 class="text-lg font-medium">學歷 #{{ $index + 1 }}</h3>
                                            <flux:button wire:click="removeEducation({{ $index }})"
                                                variant="danger" size="sm" class="shrink-0 !px-2">
                                                <flux:icon name="trash" class="w-4 h-4" />
                                                <span class="sr-only">刪除</span>
                                            </flux:button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <flux:label>學校</flux:label>
                                                <flux:input wire:model="education.{{ $index }}.school"
                                                    type="text" />
                                            </div>
                                            <div>
                                                <flux:label>學位</flux:label>
                                                <flux:input wire:model="education.{{ $index }}.degree"
                                                    type="text" />
                                            </div>
                                            <div class="sm:col-span-2">
                                                <flux:label>科系</flux:label>
                                                <flux:input wire:model="education.{{ $index }}.field"
                                                    type="text" />
                                            </div>
                                            <div>
                                                <flux:label>開始日期</flux:label>
                                                <flux:input wire:model="education.{{ $index }}.start_date"
                                                    type="date" class="w-full" />
                                            </div>
                                            <div>
                                                <flux:label>結束日期</flux:label>
                                                <flux:input wire:model="education.{{ $index }}.end_date"
                                                    type="date" class="w-full" />
                                            </div>
                                        </div>
                                        <div>
                                            <flux:label>描述</flux:label>
                                            <flux:textarea wire:model="education.{{ $index }}.description"
                                                rows="3" />
                                        </div>
                                    </div>
                                </x-card>
                            @endforeach
                            <div class="flex flex-col sm:flex-row justify-between gap-4 sm:gap-6">
                                <flux:button wire:click="addEducation" variant="ghost" class="w-full sm:w-auto order-2 sm:order-1">
                                    <flux:icon name="plus-circle" class="w-5 h-5 mr-2" />
                                    新增學歷
                                </flux:button>
                                <flux:button wire:click="updateEducation" variant="primary" class="w-full sm:w-auto order-1 sm:order-2">
                                    <flux:icon name="check-circle" class="w-5 h-5 mr-2" />
                                    儲存學歷資料
                                </flux:button>
                            </div>
                        </div>
                    </div>

                    <!-- 工作經驗表單 -->
                    <div x-show="$wire.currentTab === 'experience'" class="space-y-4">
                        @foreach ($experience as $index => $exp)
                            <x-card>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-start flex-wrap gap-2">
                                        <h3 class="text-lg font-medium">工作經驗 #{{ $index + 1 }}</h3>
                                        <flux:button wire:click="removeExperience({{ $index }})"
                                            variant="danger" size="sm" class="shrink-0 !px-2">
                                            <flux:icon name="trash" class="w-4 h-4" />
                                            <span class="sr-only">刪除</span>
                                        </flux:button>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <flux:label>公司</flux:label>
                                            <flux:input wire:model="experience.{{ $index }}.company"
                                                type="text" />
                                        </div>
                                        <div>
                                            <flux:label>職位</flux:label>
                                            <flux:input wire:model="experience.{{ $index }}.position"
                                                type="text" />
                                        </div>
                                        <div>
                                            <flux:label>開始日期</flux:label>
                                                <flux:input wire:model="experience.{{ $index }}.start_date"
                                                type="date" class="w-full" />
                                            </div>
                                            <div>
                                                <flux:label>結束日期</flux:label>
                                                <flux:input wire:model="experience.{{ $index }}.end_date"
                                                type="date" class="w-full"
                                                    :disabled="$experience[$index]['current'] ?? false" />
                                            </div>
                                        <div class="sm:col-span-2">
                                            <flux:checkbox wire:model="experience.{{ $index }}.current"
                                                label="目前在職中" />
                                        </div>
                                    </div>
                                    <div>
                                        <flux:label>工作描述</flux:label>
                                        <flux:textarea wire:model="experience.{{ $index }}.description"
                                            rows="3" />
                                    </div>
                                </div>
                            </x-card>
                        @endforeach

                        <div class="flex flex-col sm:flex-row justify-between gap-4 sm:gap-6">
                            <flux:button wire:click="addExperience" variant="ghost" class="w-full sm:w-auto order-2 sm:order-1">
                                <flux:icon name="plus-circle" class="w-5 h-5 mr-2" />
                                新增工作經驗
                            </flux:button>
                            <flux:button wire:click="updateExperience" variant="primary" class="w-full sm:w-auto order-1 sm:order-2">
                                <flux:icon name="check-circle" class="w-5 h-5 mr-2" />
                                儲存工作經驗
                            </flux:button>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>

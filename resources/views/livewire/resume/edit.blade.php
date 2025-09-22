<?php
use function Livewire\Volt\{state, mount};
use App\Models\Resume;

state(['resume' => null, 'title' => '', 'summary' => '', 'education' => [], 'experience' => [], 'currentTab' => 'basic']);

mount(function () {
    $this->resume = auth()->user()->resume;
    if ($this->resume) {
        $this->title = $this->resume->title;
        $this->summary = $this->resume->summary;
        $this->education = $this->resume->education ?? [];
        $this->experience = $this->resume->experience ?? [];
    }
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
    $this->resume->update(['education' => $this->education]);
    
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
    $this->resume->update(['experience' => $this->experience]);
    
    $this->dispatch('notify', [
        'message' => '工作經驗已更新',
        'type' => 'success',
    ]);
};

$shouldShowCurrentOption = function ($index) {
    if ($index === 0) return true;
    
    $previousExp = $this->experience[$index - 1] ?? null;
    if (!$previousExp || !isset($previousExp['end_date']) || empty($previousExp['end_date'])) {
        return false;
    }
    
    return true;
};
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
    <!-- Header Section -->
    <div class="relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm border-b border-slate-200/50 dark:border-slate-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center space-x-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 shadow-lg">
                    <i class="fas fa-edit text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-400 bg-clip-text text-transparent">
                        編輯履歷
                    </h1>
                    <p class="mt-1 text-slate-600 dark:text-slate-400">
                        完善您的履歷資料，讓雇主更了解您
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Column - Navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden sticky top-8">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-list mr-2"></i>
                            編輯選單
                        </h3>
                    </div>
                    <div class="p-4">
                        <nav class="space-y-2">
                            <button wire:click="$set('currentTab', 'basic')"
                                class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg font-medium text-sm transition-all duration-200 {{ $currentTab === 'basic' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                                <i class="fas fa-user text-sm"></i>
                                <span>基本資料</span>
                            </button>
                            <button wire:click="$set('currentTab', 'education')"
                                class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg font-medium text-sm transition-all duration-200 {{ $currentTab === 'education' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                                <i class="fas fa-graduation-cap text-sm"></i>
                                <span>學歷</span>
                            </button>
                            <button wire:click="$set('currentTab', 'experience')"
                                class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg font-medium text-sm transition-all duration-200 {{ $currentTab === 'experience' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                                <i class="fas fa-briefcase text-sm"></i>
                                <span>工作經驗</span>
                            </button>
                            <button wire:click="$set('currentTab', 'portfolio')"
                                class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg font-medium text-sm transition-all duration-200 {{ $currentTab === 'portfolio' ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 shadow-md' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50' }}">
                                <i class="fas fa-folder text-sm"></i>
                                <span>作品集</span>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Right Column - Content -->
            <div class="lg:col-span-3">
                <!-- 基本資料表單 -->
                <div x-show="$wire.currentTab === 'basic'" class="space-y-6">
                    <div class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                基本資料
                            </h3>
                        </div>
                        <div class="p-6">
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
                                <div class="flex justify-end pt-6">
                                    <button 
                                        type="submit" 
                                        class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 active:scale-95 text-white font-semibold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-3 w-full sm:w-auto min-w-[160px]"
                                    >
                                        <i class="fas fa-check-circle text-sm"></i>
                                        <span>更新基本資料</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 學歷表單 -->
                <div x-show="$wire.currentTab === 'education'" class="space-y-6">
                    <div class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-violet-600 p-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                學歷資料
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                @foreach ($education as $index => $edu)
                                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-6 border border-slate-200 dark:border-slate-600">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="text-lg font-medium text-slate-900 dark:text-white">學歷 #{{ $index + 1 }}</h4>
                                            <button 
                                                wire:click="removeEducation({{ $index }})"
                                                class="px-4 py-2 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 border border-red-200 dark:border-red-800 hover:border-red-300 dark:hover:border-red-700 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center"
                                            >
                                                <i class="fas fa-trash text-sm mr-2"></i>
                                                <span>刪除</span>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <flux:label>學校</flux:label>
                                                <flux:input wire:model="education.{{ $index }}.school" type="text" />
                                            </div>
                                            <div>
                                                <flux:label>學位</flux:label>
                                                <flux:input wire:model="education.{{ $index }}.degree" type="text" />
                                            </div>
                                            <div class="sm:col-span-2">
                                                <flux:label>科系</flux:label>
                                                <flux:input wire:model="education.{{ $index }}.field" type="text" />
                                            </div>
                                            <div>
                                                <flux:label>開始日期</flux:label>
                                                <flux:input wire:model="education.{{ $index }}.start_date" type="date" class="w-full" />
                                            </div>
                                            <div>
                                                <flux:label>結束日期</flux:label>
                                                <flux:input wire:model="education.{{ $index }}.end_date" type="date" class="w-full" />
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <flux:label>描述</flux:label>
                                            <flux:textarea wire:model="education.{{ $index }}.description" rows="3" />
                                        </div>
                                    </div>
                                @endforeach
                                
                                <div class="flex flex-col sm:flex-row justify-between gap-4 sm:gap-6 pt-6">
                                    <button 
                                        wire:click="addEducation" 
                                        class="w-full sm:w-auto order-2 sm:order-1 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white font-semibold px-6 py-4 rounded-xl shadow-md hover:shadow-lg border border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500 transition-all duration-200 flex items-center justify-center space-x-3 min-w-[140px]"
                                    >
                                        <i class="fas fa-plus-circle text-sm"></i>
                                        <span>新增學歷</span>
                                    </button>
                                    <button 
                                        wire:click="updateEducation" 
                                        class="w-full sm:w-auto order-1 sm:order-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 active:scale-95 text-white font-semibold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-3 min-w-[160px]"
                                    >
                                        <i class="fas fa-check-circle text-sm"></i>
                                        <span>儲存學歷資料</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 工作經驗表單 -->
                <div x-show="$wire.currentTab === 'experience'" class="space-y-6">
                    <div class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-500 to-red-600 p-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <i class="fas fa-briefcase mr-2"></i>
                                工作經驗
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                @foreach ($experience as $index => $exp)
                                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-6 border border-slate-200 dark:border-slate-600">
                                        <div class="flex justify-between items-start mb-4">
                                            <h4 class="text-lg font-medium text-slate-900 dark:text-white">工作經驗 #{{ $index + 1 }}</h4>
                                            <button 
                                                wire:click="removeExperience({{ $index }})"
                                                class="px-4 py-2 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 border border-red-200 dark:border-red-800 hover:border-red-300 dark:hover:border-red-700 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center"
                                            >
                                                <i class="fas fa-trash text-sm mr-2"></i>
                                                <span>刪除</span>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <flux:label>公司</flux:label>
                                                <flux:input wire:model="experience.{{ $index }}.company" type="text" />
                                            </div>
                                            <div>
                                                <flux:label>職位</flux:label>
                                                <flux:input wire:model="experience.{{ $index }}.position" type="text" />
                                            </div>
                                            <div>
                                                <flux:label>開始日期</flux:label>
                                                <flux:input wire:model="experience.{{ $index }}.start_date" type="date" class="w-full" />
                                            </div>
                                            @if(!($experience[$index]['current'] ?? false))
                                                <div>
                                                    <flux:label>結束日期</flux:label>
                                                    <flux:input wire:model="experience.{{ $index }}.end_date" type="date" class="w-full" />
                                                </div>
                                            @endif
                                            @if($this->shouldShowCurrentOption($index))
                                                <div class="sm:col-span-2">
                                                    <flux:checkbox wire:model="experience.{{ $index }}.current" label="目前在職中" />
                                                    @if(($experience[$index]['current'] ?? false))
                                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1 flex items-center">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            已勾選：結束日期欄位已隱藏
                                                        </p>
                                                    @else
                                                        <p class="text-sm text-blue-600 dark:text-blue-400 mt-1 flex items-center">
                                                            <i class="fas fa-info-circle mr-1"></i>
                                                            勾選後將隱藏結束日期欄位，取消勾選將重新顯示並自動填入預設日期
                                                        </p>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="sm:col-span-2">
                                                    <div class="text-sm text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800 px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        此工作經驗的開始日期必須在之前工作結束之後，才能設定為目前工作
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mt-4">
                                            <flux:label>工作描述</flux:label>
                                            <flux:textarea wire:model="experience.{{ $index }}.description" rows="3" />
                                        </div>
                                    </div>
                                @endforeach

                                <div class="flex flex-col sm:flex-row justify-between gap-4 sm:gap-6 pt-6">
                                    <button 
                                        wire:click="addExperience" 
                                        class="w-full sm:w-auto order-2 sm:order-1 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white font-semibold px-6 py-4 rounded-xl shadow-md hover:shadow-lg border border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500 transition-all duration-200 flex items-center justify-center space-x-3 min-w-[160px]"
                                    >
                                        <i class="fas fa-plus-circle text-sm"></i>
                                        <span>新增工作經驗</span>
                                    </button>
                                    <button 
                                        wire:click="updateExperience" 
                                        class="w-full sm:w-auto order-1 sm:order-2 bg-gradient-to-r from-purple-500 to-violet-600 hover:from-purple-600 hover:to-violet-700 active:scale-95 text-white font-semibold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-3 min-w-[160px]"
                                    >
                                        <i class="fas fa-check-circle text-sm"></i>
                                        <span>儲存工作經驗</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 作品集管理 -->
                <div x-show="$wire.currentTab === 'portfolio'" class="space-y-6">
                    <div class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-500 to-pink-600 p-4">
                            <h3 class="text-lg font-semibold text-white flex items-center">
                                <i class="fas fa-folder mr-2"></i>
                                作品集管理
                            </h3>
                        </div>
                        <div class="p-6">
                            <livewire:resume.portfolio.project-list :resumeId="$resume->id" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
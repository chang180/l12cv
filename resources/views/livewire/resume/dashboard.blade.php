<?php
use function Livewire\Volt\{state, mount};
use App\Models\Resume;

state(['resume' => null, 'isPublic' => false, 'search' => '', 'filter' => 'all']);

mount(function () {
    $this->resume = auth()->user()->resume;
    if ($this->resume) {
        $this->isPublic = (int) $this->resume->is_public;
    } else {
        $this->resume = null;
        $this->isPublic = false;
    }
});

$edit = function () {
    return $this->redirect(route('resume.edit'), navigate: true);
};

$settings = function () {
    return $this->redirect(route('resume.settings'), navigate: true);
};

$updateVisibility = function ($value) {
    $value = (int) $value;
    $this->resume->update(['is_public' => $value]);
    $this->isPublic = $value;
    
    // 刷新履歷資料以確保狀態同步
    $this->resume = $this->resume->fresh();
    
    // 觸發事件通知側邊欄更新
    $this->dispatch('resume-visibility-updated', [
        'isPublic' => $value == 1
    ]);
};

$create = function () {
    $user = auth()->user();
    $baseSlug = \Illuminate\Support\Str::slug($user->name);
    $slug = $baseSlug;
    $counter = 1;
    
    // 確保 slug 的唯一性
    while (\App\Models\Resume::where('slug', $slug)->exists()) {
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }
    
    $resume = $user
        ->resume()
        ->create([
            'title' => $user->name . ' 的履歷',
            'slug' => $slug,
            'summary' => '',
            'education' => [],
            'experience' => [],
            'is_public' => false,
        ]);

    return $this->redirect(route('resume.edit'), navigate: true);
};

$filterResumes = function ($filter) {
    $this->filter = $filter;
};

$searchResumes = function () {
    // 這裡可以實現搜尋邏輯
};
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
    <!-- Header Section -->
    <div class="relative bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm border-b border-slate-200/50 dark:border-slate-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-900 to-slate-600 dark:from-slate-100 dark:to-slate-400 bg-clip-text text-transparent">
                        履歷管理中心
                    </h1>
                    <p class="mt-2 text-slate-600 dark:text-slate-400">
                        管理您的履歷，打造專業形象
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Search Bar -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-slate-400"></i>
                        </div>
                        <input 
                            wire:model.live.debounce.300ms="search" 
                            type="text" 
                            placeholder="搜尋履歷..." 
                            class="w-64 pl-10 pr-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                        >
                    </div>
                    
                    <!-- Filter Dropdown -->
                    <flux:dropdown>
                        <flux:button variant="outline" icon="funnel" class="transition-all duration-200 hover:scale-105">
                            篩選
                        </flux:button>
                        <flux:menu>
                            <flux:menu.item wire:click="filterResumes('all')" :active="$filter === 'all'">
                                全部履歷
                            </flux:menu.item>
                            <flux:menu.item wire:click="filterResumes('public')" :active="$filter === 'public'">
                                公開履歷
                            </flux:menu.item>
                            <flux:menu.item wire:click="filterResumes('private')" :active="$filter === 'private'">
                                私人履歷
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                    
                    <!-- Dark Mode Toggle -->
                    <button 
                        x-data="{ 
                            darkMode: $flux.appearance === 'dark',
                            toggle() { 
                                this.darkMode = !this.darkMode;
                                $flux.appearance = this.darkMode ? 'dark' : 'light';
                            }
                        }"
                        @click="toggle()"
                        class="p-2 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 transition-all duration-200 hover:scale-105"
                        title="切換深色模式"
                    >
                        <i x-show="!darkMode" class="fas fa-moon text-slate-600 dark:text-slate-400 text-lg"></i>
                        <i x-show="darkMode" class="fas fa-sun text-yellow-500 text-lg"></i>
                    </button>
                    
                    <!-- User Profile Dropdown -->
                    <flux:dropdown>
                        <flux:button variant="outline" class="flex items-center space-x-2 transition-all duration-200 hover:scale-105">
                            <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" class="w-6 h-6 rounded-full">
                            <span class="hidden sm:block">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </flux:button>
                        <flux:menu>
                            <flux:menu.item :href="route('settings.profile')" icon="user">
                                個人資料
                            </flux:menu.item>
                            <flux:menu.item :href="route('settings.password')" icon="lock-closed">
                                修改密碼
                            </flux:menu.item>
                            <flux:menu.item :href="route('settings.appearance')" icon="paint-brush">
                                外觀設定
                            </flux:menu.item>
                            <flux:menu.separator />
                            <flux:menu.item href="/logout" icon="arrow-right-start-on-rectangle">
                                登出
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Resume Management -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Resume Card -->
                <div class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
                    <div class="p-8">
                        @if ($resume)
                            <div x-data="{
                                resume: @js($resume ?? []),
                                isPublic: Number(@js($resume?->is_public ?? 0)),
                                slug: @js($resume?->slug ?? ''),
                                title: @js($resume?->title ?? '')
                            }" class="space-y-6">
                                <!-- Resume Header -->
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-3 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl">
                                            <i class="fas fa-file-alt text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100" x-text="title"></h3>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                      :class="isPublic == 1 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'"
                                                      x-text="isPublic == 1 ? '公開' : '私人'">
                                                </span>
                                                <span class="text-sm text-slate-500 dark:text-slate-400" x-text="'@' + slug"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Quick Actions -->
                                    <div class="flex items-center space-x-2">
                                        <flux:button wire:click="edit" variant="primary" size="sm">
                                            <i class="fas fa-edit mr-2"></i>
                                            編輯
                                        </flux:button>
                                        @if($resume && $resume->is_public)
                                            <flux:button :href="route('resume.public', ['slug' => $resume->slug])" target="_blank" variant="outline" size="sm">
                                                <i class="fas fa-eye mr-2"></i>
                                                預覽
                                            </flux:button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Resume Stats -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-8 border border-blue-200/50 dark:border-blue-700/50 hover:shadow-lg transition-all duration-300 hover:scale-105">
                                        <div class="text-center">
                                            <div class="flex justify-center mb-4">
                                                <div class="p-4 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg">
                                                    <i class="fas fa-graduation-cap text-white text-2xl"></i>
                                                </div>
                                            </div>
                                            <p class="text-lg font-medium text-blue-700 dark:text-blue-300 mb-2">學歷項目</p>
                                            <p class="text-4xl font-bold text-blue-900 dark:text-blue-100" x-text="resume.education ? resume.education.length : 0"></p>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-8 border border-green-200/50 dark:border-green-700/50 hover:shadow-lg transition-all duration-300 hover:scale-105">
                                        <div class="text-center">
                                            <div class="flex justify-center mb-4">
                                                <div class="p-4 bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg">
                                                    <i class="fas fa-briefcase text-white text-2xl"></i>
                                                </div>
                                            </div>
                                            <p class="text-lg font-medium text-green-700 dark:text-green-300 mb-2">工作經驗</p>
                                            <p class="text-4xl font-bold text-green-900 dark:text-green-100" x-text="resume.experience ? resume.experience.length : 0"></p>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-8 border border-purple-200/50 dark:border-purple-700/50 hover:shadow-lg transition-all duration-300 hover:scale-105">
                                        <div class="text-center">
                                            <div class="flex justify-center mb-4">
                                                <div class="p-4 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                                    <i class="fas fa-eye text-white text-2xl"></i>
                                                </div>
                                            </div>
                                            <p class="text-lg font-medium text-purple-700 dark:text-purple-300 mb-2">履歷瀏覽</p>
                                            <p class="text-4xl font-bold text-purple-900 dark:text-purple-100">{{ $resume->views ?? 0 }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-8 border border-orange-200/50 dark:border-orange-700/50 hover:shadow-lg transition-all duration-300 hover:scale-105">
                                        <div class="text-center">
                                            <div class="flex justify-center mb-4">
                                                <div class="p-4 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl shadow-lg">
                                                    <i class="fas fa-folder-open text-white text-2xl"></i>
                                                </div>
                                            </div>
                                            <p class="text-lg font-medium text-orange-700 dark:text-orange-300 mb-2">作品集瀏覽</p>
                                            <p class="text-4xl font-bold text-orange-900 dark:text-orange-100">{{ auth()->user()->projects->sum('views') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Visibility Toggle -->
                                <div class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-700/50 dark:to-slate-800/50 rounded-xl p-6 border border-slate-200/50 dark:border-slate-600/50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="p-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg">
                                                <i class="fas fa-globe text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-slate-900 dark:text-slate-100">公開狀態</h4>
                                                <p class="text-xs text-slate-600 dark:text-slate-400">控制履歷的公開可見性</p>
                                            </div>
                                        </div>
                                        <flux:radio.group x-model="isPublic" variant="segmented" class="bg-white dark:bg-slate-700 p-1 rounded-lg shadow-sm"
                                            wire:change="updateVisibility($event.target.value)">
                                            <flux:radio value="1" icon="eye" class="transition-all duration-200">
                                                公開
                                            </flux:radio>
                                            <flux:radio value="0" icon="eye-slash" class="transition-all duration-200">
                                                私人
                                            </flux:radio>
                                        </flux:radio.group>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- No Resume State -->
                            <div class="text-center py-12">
                                <div class="mx-auto w-24 h-24 bg-gradient-to-r from-blue-100 to-purple-100 dark:from-blue-900/50 dark:to-purple-900/50 rounded-full flex items-center justify-center mb-6">
                                    <i class="fas fa-file-alt text-3xl text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2">還沒有履歷</h3>
                                <p class="text-slate-600 dark:text-slate-400 mb-8 max-w-md mx-auto">
                                    開始建立您的第一份專業履歷，讓您的職涯更加精彩！
                                </p>
                                <flux:button wire:click="create" variant="primary" size="lg">
                                    <i class="fas fa-plus mr-2"></i>
                                    建立履歷
                                </flux:button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Quick Actions & Settings -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-bolt mr-2"></i>
                            快速操作
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <flux:button href="{{ route('resume.edit') }}" variant="outline" class="w-full justify-start group hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 hover:scale-105">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg mr-3 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition-colors">
                                        <i class="fas fa-edit text-blue-600 dark:text-blue-400"></i>
                                    </div>
                                    <span>編輯履歷</span>
                                </div>
                            </flux:button>
                            
                            <flux:button href="{{ route('resume.settings') }}" variant="outline" class="w-full justify-start group hover:bg-orange-50 dark:hover:bg-orange-900/20 transition-all duration-200 hover:scale-105">
                                <div class="flex items-center">
                                    <div class="p-2 bg-orange-100 dark:bg-orange-900/50 rounded-lg mr-3 group-hover:bg-orange-200 dark:group-hover:bg-orange-800/50 transition-colors">
                                        <i class="fas fa-cog text-orange-600 dark:text-orange-400"></i>
                                    </div>
                                    <span>履歷設定</span>
                                </div>
                            </flux:button>
                            
                            @if($resume && $resume->is_public)
                                <flux:button :href="route('resume.public', ['slug' => $resume->slug])" target="_blank" variant="outline" class="w-full justify-start group hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200 hover:scale-105">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-purple-100 dark:bg-purple-900/50 rounded-lg mr-3 group-hover:bg-purple-200 dark:group-hover:bg-purple-800/50 transition-colors">
                                            <i class="fas fa-eye text-purple-600 dark:text-purple-400"></i>
                                        </div>
                                        <span>預覽履歷</span>
                                    </div>
                                </flux:button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Account Settings -->
                <div class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-pink-600 p-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-user-cog mr-2"></i>
                            帳戶設定
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <flux:button href="/settings/profile" variant="outline" class="w-full justify-start group hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all duration-200 hover:scale-105">
                                <div class="flex items-center">
                                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg mr-3 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-800/50 transition-colors">
                                        <i class="fas fa-user text-indigo-600 dark:text-indigo-400"></i>
                                    </div>
                                    <span>個人資料</span>
                                </div>
                            </flux:button>
                            
                            <flux:button href="/settings/password" variant="outline" class="w-full justify-start group hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200 hover:scale-105">
                                <div class="flex items-center">
                                    <div class="p-2 bg-red-100 dark:bg-red-900/50 rounded-lg mr-3 group-hover:bg-red-200 dark:group-hover:bg-red-800/50 transition-colors">
                                        <i class="fas fa-lock text-red-600 dark:text-red-400"></i>
                                    </div>
                                    <span>密碼設定</span>
                                </div>
                            </flux:button>
                            
                            <flux:button href="/settings/appearance" variant="outline" class="w-full justify-start group hover:bg-pink-50 dark:hover:bg-pink-900/20 transition-all duration-200 hover:scale-105">
                                <div class="flex items-center">
                                    <div class="p-2 bg-pink-100 dark:bg-pink-900/50 rounded-lg mr-3 group-hover:bg-pink-200 dark:group-hover:bg-pink-800/50 transition-colors">
                                        <i class="fas fa-palette text-pink-600 dark:text-pink-400"></i>
                                    </div>
                                    <span>外觀設定</span>
                                </div>
                            </flux:button>
                        </div>
                    </div>
                </div>

                <!-- Tips & Help -->
                <div class="bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 hover:scale-105">
                    <div class="p-6 text-white relative">
                        <div class="absolute top-4 right-4">
                            <i class="fas fa-lightbulb text-yellow-300 text-2xl opacity-50"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-3 flex items-center">
                            <i class="fas fa-star mr-2 text-yellow-300"></i>
                            專業建議
                        </h3>
                        <p class="text-blue-100 text-sm leading-relaxed mb-4">
                            定期更新您的履歷內容，保持資訊的時效性。公開履歷可以讓更多雇主發現您！
                        </p>
                        <div class="flex items-center text-xs text-blue-200">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <span>您的資料受到安全保護</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

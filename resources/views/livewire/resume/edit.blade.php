<div>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-purple-600">
                    <i class="fas fa-edit text-white text-lg"></i>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">
                        編輯履歷
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        完善您的履歷資料，讓雇主更了解您
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a 
                    href="/resume" 
                    class="hidden sm:flex items-center justify-center space-x-3 px-5 py-3 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 min-w-[140px]"
                >
                    <flux:icon name="arrow-left" class="w-4 h-4 flex-shrink-0" />
                    <span class="font-medium">返回控制台</span>
                </a>
                <button 
                    wire:click="$dispatch('save')" 
                    class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 active:scale-95 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-3 min-w-[140px]"
                >
                    <flux:icon name="check-circle" class="w-5 h-5 flex-shrink-0" />
                    <span>儲存變更</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="p-6">
                    <!-- 分頁標籤 -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-xl p-2 mb-8">
                        <nav class="flex space-x-2 overflow-x-auto">
                            <button wire:click="$set('currentTab', 'basic')"
                                class="flex items-center space-x-2 px-4 py-3 rounded-lg font-medium text-sm whitespace-nowrap transition-all duration-200 {{ $currentTab === 'basic' ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-white/50 dark:hover:bg-gray-700/50' }}">
                                <i class="fas fa-user text-sm"></i>
                                <span>基本資料</span>
                            </button>
                            <button wire:click="$set('currentTab', 'education')"
                                class="flex items-center space-x-2 px-4 py-3 rounded-lg font-medium text-sm whitespace-nowrap transition-all duration-200 {{ $currentTab === 'education' ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-white/50 dark:hover:bg-gray-700/50' }}">
                                <i class="fas fa-graduation-cap text-sm"></i>
                                <span>學歷</span>
                            </button>
                            <button wire:click="$set('currentTab', 'experience')"
                                class="flex items-center space-x-2 px-4 py-3 rounded-lg font-medium text-sm whitespace-nowrap transition-all duration-200 {{ $currentTab === 'experience' ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-white/50 dark:hover:bg-gray-700/50' }}">
                                <i class="fas fa-briefcase text-sm"></i>
                                <span>工作經驗</span>
                            </button>
                            <button wire:click="$set('currentTab', 'portfolio')"
                                class="flex items-center space-x-2 px-4 py-3 rounded-lg font-medium text-sm whitespace-nowrap transition-all duration-200 {{ $currentTab === 'portfolio' ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-white/50 dark:hover:bg-gray-700/50' }}">
                                <i class="fas fa-folder text-sm"></i>
                                <span>作品集</span>
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
                                <div class="flex justify-end pt-6">
                                    <button 
                                        type="submit" 
                                        class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 active:scale-95 text-white font-semibold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-3 w-full sm:w-auto min-w-[160px]"
                                    >
                                        <flux:icon name="check-circle" class="w-5 h-5 flex-shrink-0" />
                                        <span>更新基本資料</span>
                                    </button>
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
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">學歷 #{{ $index + 1 }}</h3>
                                            <button 
                                                wire:click="removeEducation({{ $index }})"
                                                class="shrink-0 px-4 py-2 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 border border-red-200 dark:border-red-800 hover:border-red-300 dark:hover:border-red-700 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center"
                                            >
                                                <flux:icon name="trash" class="w-4 h-4" />
                                                <span class="sr-only">刪除學歷</span>
                                            </button>
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
                            <div class="flex flex-col sm:flex-row justify-between gap-4 sm:gap-6 pt-8">
                                <button 
                                    wire:click="addEducation" 
                                    class="w-full sm:w-auto order-2 sm:order-1 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white font-semibold px-6 py-4 rounded-xl shadow-md hover:shadow-lg border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 transition-all duration-200 flex items-center justify-center space-x-3 min-w-[140px]"
                                >
                                    <flux:icon name="plus-circle" class="w-5 h-5 flex-shrink-0" />
                                    <span>新增學歷</span>
                                </button>
                                <button 
                                    wire:click="updateEducation" 
                                    class="w-full sm:w-auto order-1 sm:order-2 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 active:scale-95 text-white font-semibold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-3 min-w-[160px]"
                                >
                                    <flux:icon name="check-circle" class="w-5 h-5 flex-shrink-0" />
                                    <span>儲存學歷資料</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 工作經驗表單 -->
                    <div x-show="$wire.currentTab === 'experience'" class="space-y-4">
                        @foreach ($experience as $index => $exp)
                            <x-card>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-start flex-wrap gap-2">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">工作經驗 #{{ $index + 1 }}</h3>
                                        <button 
                                            wire:click="removeExperience({{ $index }})"
                                            class="shrink-0 px-4 py-2 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 border border-red-200 dark:border-red-800 hover:border-red-300 dark:hover:border-red-700 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center"
                                        >
                                            <flux:icon name="trash" class="w-4 h-4" />
                                            <span class="sr-only">刪除工作經驗</span>
                                        </button>
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
                                        @if(!($experience[$index]['current'] ?? false))
                                            <div>
                                                <flux:label>結束日期</flux:label>
                                                <flux:input wire:model="experience.{{ $index }}.end_date"
                                                    type="date" class="w-full" />
                                            </div>
                                        @endif
                                        @if($this->shouldShowCurrentOption($index))
                                            <div class="sm:col-span-2">
                                                <flux:checkbox wire:model="experience.{{ $index }}.current"
                                                    label="目前在職中" />
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
                                                <div class="text-sm text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    此工作經驗的開始日期必須在之前工作結束之後，才能設定為目前工作
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <flux:label>工作描述</flux:label>
                                        <flux:textarea wire:model="experience.{{ $index }}.description"
                                            rows="3" />
                                    </div>
                                </div>
                            </x-card>
                        @endforeach

                        <div class="flex flex-col sm:flex-row justify-between gap-4 sm:gap-6 pt-8">
                            <button 
                                wire:click="addExperience" 
                                class="w-full sm:w-auto order-2 sm:order-1 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white font-semibold px-6 py-4 rounded-xl shadow-md hover:shadow-lg border border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500 transition-all duration-200 flex items-center justify-center space-x-3 min-w-[160px]"
                            >
                                <flux:icon name="plus-circle" class="w-5 h-5 flex-shrink-0" />
                                <span>新增工作經驗</span>
                            </button>
                            <button 
                                wire:click="updateExperience" 
                                class="w-full sm:w-auto order-1 sm:order-2 bg-gradient-to-r from-purple-500 to-violet-600 hover:from-purple-600 hover:to-violet-700 active:scale-95 text-white font-semibold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-3 min-w-[160px]"
                            >
                                <flux:icon name="check-circle" class="w-5 h-5 flex-shrink-0" />
                                <span>儲存工作經驗</span>
                            </button>
                        </div>
                    </div>

                    <!-- 作品集管理 - 使用組件 -->
                    <div x-show="$wire.currentTab === 'portfolio'" class="space-y-4">
                        <!-- 使用作品集組件 -->
                        <livewire:resume.portfolio.project-list :resumeId="$resume->id" />
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
<div>
    <flux:modal wire:model="isVisible" variant="flyout" class="max-w-4xl">
        <x-slot name="title">
            <div class="flex items-center space-x-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-indigo-500 to-pink-600">
                    <i class="fas fa-folder-plus text-white text-sm"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">
                        {{ $projectId ? '編輯項目' : '新增項目' }}
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        {{ $projectId ? '更新您的項目資訊' : '添加新項目到您的作品集' }}
                    </p>
                </div>
            </div>
        </x-slot>

        <div class="p-6">
            <form wire:submit.prevent="saveProject" class="space-y-8">
                <!-- 基本資訊區塊 -->
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700 rounded-xl p-6 border border-slate-200 dark:border-slate-600">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg">
                            <i class="fas fa-info-circle text-white text-sm"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-white">基本資訊</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- 項目標題 -->
                        <div>
                            <flux:label for="title" class="text-slate-700 dark:text-slate-300 font-medium">
                                項目標題 <span class="text-red-500">*</span>
                            </flux:label>
                            <flux:input 
                                wire:model="title" 
                                id="title" 
                                type="text" 
                                required 
                                placeholder="輸入您的項目標題"
                                class="mt-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400" 
                            />
                            @error('title') <flux:error :messages="$message" /> @enderror
                        </div>

                        <!-- 項目描述 -->
                        <div>
                            <flux:label for="description" class="text-slate-700 dark:text-slate-300 font-medium">項目描述</flux:label>
                            <flux:textarea 
                                wire:model="description" 
                                id="description" 
                                rows="4" 
                                placeholder="描述您的項目，包括其目的、功能和您的貢獻..."
                                class="mt-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400" 
                            />
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                <i class="fas fa-lightbulb mr-1"></i>
                                簡要描述您的項目，包括其目的、功能和您的貢獻
                            </p>
                            @error('description') <flux:error :messages="$message" /> @enderror
                        </div>
                    </div>
                </div>

                <!-- 縮略圖上傳區塊 -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-6 border border-purple-200 dark:border-purple-700">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg">
                            <i class="fas fa-image text-white text-sm"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-white">項目縮略圖</h4>
                    </div>
                    
                    <div class="flex items-start space-x-6">
                        <!-- 縮略圖預覽 -->
                        <div class="flex-shrink-0">
                            @if ($thumbnailPreview && !$removeThumbnail)
                                <img src="{{ $thumbnailPreview }}" alt="縮略圖預覽" class="h-32 w-32 object-cover rounded-xl shadow-lg border-2 border-white dark:border-slate-600">
                            @elseif ($existingThumbnail && !$removeThumbnail)
                                <img src="{{ $existingThumbnail }}" alt="現有縮略圖" class="h-32 w-32 object-cover rounded-xl shadow-lg border-2 border-white dark:border-slate-600">
                            @else
                                <div class="h-32 w-32 rounded-xl bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center shadow-lg border-2 border-dashed border-slate-300 dark:border-slate-600">
                                    <div class="text-center">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 dark:text-slate-500 mb-2"></i>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">上傳圖片</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- 上傳控制 -->
                        <div class="flex-1 space-y-4">
                            <div x-data="{ uploading: false, progress: 0 }"
                                x-on:livewire-upload-start="uploading = true"
                                x-on:livewire-upload-finish="uploading = false"
                                x-on:livewire-upload-error="uploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress">
                                
                                <label for="thumbnail-upload" class="cursor-pointer">
                                    <div class="bg-gradient-to-r from-indigo-500 to-pink-600 hover:from-indigo-600 hover:to-pink-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-3 group">
                                        <div class="p-1 bg-white/20 rounded-lg group-hover:bg-white/30 transition-colors">
                                            <i class="fas fa-upload text-sm"></i>
                                        </div>
                                        <span>選擇圖片</span>
                                    </div>
                                    <input id="thumbnail-upload" wire:model="thumbnail" type="file" class="sr-only" accept="image/*">
                                </label>
                                
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    支援 PNG, JPG, GIF 格式，最大 1MB
                                </p>

                                @if (($thumbnailPreview || $existingThumbnail) && !$removeThumbnail)
                                    <button 
                                        type="button" 
                                        wire:click="$set('removeThumbnail', true)" 
                                        class="mt-3 text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 flex items-center space-x-2 transition-colors"
                                    >
                                        <i class="fas fa-trash text-xs"></i>
                                        <span>移除圖片</span>
                                    </button>
                                @elseif ($removeThumbnail)
                                    <button 
                                        type="button" 
                                        wire:click="$set('removeThumbnail', false)" 
                                        class="mt-3 text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 flex items-center space-x-2 transition-colors"
                                    >
                                        <i class="fas fa-undo text-xs"></i>
                                        <span>恢復圖片</span>
                                    </button>
                                @endif

                                <!-- 上傳進度條 -->
                                <div x-show="uploading" class="mt-4">
                                    <div class="bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                        <div class="bg-gradient-to-r from-indigo-500 to-pink-600 h-2 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 text-center">
                                        上傳中... <span x-text="progress"></span>%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('thumbnail') <flux:error :messages="$message" /> @enderror
                </div>

                <!-- 鏈接和技術區塊 -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-6 border border-green-200 dark:border-green-700">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg">
                            <i class="fas fa-link text-white text-sm"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-white">鏈接與技術</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- 項目鏈接 -->
                        <div>
                            <flux:label for="url" class="text-slate-700 dark:text-slate-300 font-medium">項目演示鏈接</flux:label>
                            <flux:input 
                                wire:model="url" 
                                id="url" 
                                type="url" 
                                placeholder="https://example.com" 
                                class="mt-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400" 
                            />
                            @error('url') <flux:error :messages="$message" /> @enderror
                        </div>

                        <!-- GitHub 鏈接 -->
                        <div>
                            <flux:label for="githubUrl" class="text-slate-700 dark:text-slate-300 font-medium">GitHub 鏈接</flux:label>
                            <flux:input 
                                wire:model="githubUrl" 
                                id="githubUrl" 
                                type="url" 
                                placeholder="https://github.com/username/repo" 
                                class="mt-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400" 
                            />
                            @error('githubUrl') <flux:error :messages="$message" /> @enderror
                        </div>

                        <!-- 技術標籤 -->
                        <div>
                            <flux:label for="technologies" class="text-slate-700 dark:text-slate-300 font-medium">使用的技術</flux:label>
                            <flux:input 
                                wire:model="technologies" 
                                id="technologies" 
                                type="text" 
                                placeholder="PHP, Laravel, Vue.js, Tailwind CSS" 
                                class="mt-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400" 
                            />
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                <i class="fas fa-tags mr-1"></i>
                                使用逗號分隔多個技術標籤
                            </p>
                            @error('technologies') <flux:error :messages="$message" /> @enderror
                        </div>

                        <!-- 完成日期 -->
                        <div>
                            <flux:label for="completionDate" class="text-slate-700 dark:text-slate-300 font-medium">完成日期</flux:label>
                            <flux:input 
                                wire:model="completionDate" 
                                id="completionDate" 
                                type="date" 
                                class="mt-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white" 
                            />
                            @error('completionDate') <flux:error :messages="$message" /> @enderror
                        </div>
                    </div>
                </div>

                <!-- 設定區塊 -->
                <div class="bg-gradient-to-r from-orange-50 to-yellow-50 dark:from-orange-900/20 dark:to-yellow-900/20 rounded-xl p-6 border border-orange-200 dark:border-orange-700">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="p-2 bg-gradient-to-r from-orange-500 to-yellow-600 rounded-lg">
                            <i class="fas fa-cog text-white text-sm"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-900 dark:text-white">項目設定</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- 特色項目 -->
                        <div class="space-y-3">
                            <flux:checkbox 
                                wire:model="isFeatured" 
                                id="isFeatured" 
                                label="設為特色項目" 
                                class="dark:text-slate-300" 
                            />
                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                <i class="fas fa-star mr-1"></i>
                                特色項目將在作品集頁面中被突出顯示
                            </p>
                            @error('isFeatured') <flux:error :messages="$message" /> @enderror
                        </div>

                        <!-- 排序順序 -->
                        <div>
                            <flux:label for="order" class="text-slate-700 dark:text-slate-300 font-medium">排序順序</flux:label>
                            <flux:input 
                                wire:model="order" 
                                id="order" 
                                type="number" 
                                min="0" 
                                class="mt-2 dark:bg-slate-700 dark:border-slate-600 dark:text-white" 
                            />
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                <i class="fas fa-sort mr-1"></i>
                                較小的數字將排在前面
                            </p>
                            @error('order') <flux:error :messages="$message" /> @enderror
                        </div>
                    </div>
                </div>

                <!-- 按鈕區域 -->
                <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <button 
                        type="button" 
                        wire:click="closeProjectForm" 
                        class="w-full sm:w-auto px-6 py-3 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white font-semibold rounded-xl shadow-md hover:shadow-lg border border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500 transition-all duration-200 flex items-center justify-center space-x-3"
                    >
                        <i class="fas fa-times text-sm"></i>
                        <span>取消</span>
                    </button>
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto bg-gradient-to-r from-indigo-500 to-pink-600 hover:from-indigo-600 hover:to-pink-700 active:scale-95 text-white font-semibold px-8 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-3 group"
                    >
                        <div class="p-1 bg-white/20 rounded-lg group-hover:bg-white/30 transition-colors">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                        <span>{{ $projectId ? '更新項目' : '創建項目' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
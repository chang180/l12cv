<div>
    <flux:modal wire:model="isVisible" variant="flyout">
        <x-slot name="title">
            {{ $projectId ? '編輯項目' : '新增項目' }}
        </x-slot>

        <div class="p-1">
            <form wire:submit.prevent="saveProject" class="space-y-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- 項目標題 -->
                    <div class="sm:col-span-6">
                        <flux:label for="title" class="text-gray-700 dark:text-gray-300">
                            項目標題 <span class="text-red-500">*</span>
                        </flux:label>
                        <flux:input wire:model="title" id="title" type="text" required class="mt-1 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        @error('title') <flux:error :messages="$message" /> @enderror
                    </div>

                    <!-- 項目描述 -->
                    <div class="sm:col-span-6 pt-2">
                        <flux:label for="description" class="text-gray-700 dark:text-gray-300">項目描述</flux:label>
                        <flux:textarea wire:model="description" id="description" rows="3" class="mt-1 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            簡要描述您的項目，包括其目的、功能和您的貢獻。
                        </p>
                        @error('description') <flux:error :messages="$message" /> @enderror
                    </div>

                    <!-- 縮略圖上傳 -->
                    <div class="sm:col-span-6 pt-2">
                        <flux:label for="thumbnail" class="text-gray-700 dark:text-gray-300 mb-2 block">項目縮略圖</flux:label>
                        <div class="mt-2 flex items-center">
                            <div x-data="{ uploading: false, progress: 0 }"
                                x-on:livewire-upload-start="uploading = true"
                                x-on:livewire-upload-finish="uploading = false"
                                x-on:livewire-upload-error="uploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress">

                                <div class="flex items-center space-x-4">
                                    @if ($thumbnailPreview)
                                        <img src="{{ $thumbnailPreview }}" alt="縮略圖預覽" class="h-24 w-24 object-cover rounded-md">
                                    @elseif ($existingThumbnail && !$removeThumbnail)
                                        <img src="{{ $existingThumbnail }}" alt="現有縮略圖" class="h-24 w-24 object-cover rounded-md">
                                    @else
                                        <div class="h-24 w-24 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif

                                    <div>
                                        <label for="thumbnail-upload" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300 focus-within:outline-none">
                                            <span>上傳圖片</span>
                                            <input id="thumbnail-upload" wire:model="thumbnail" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            PNG, JPG, GIF 最大 1MB
                                        </p>

                                        @if (($thumbnailPreview || $existingThumbnail) && !$removeThumbnail)
                                            <button type="button" wire:click="$set('removeThumbnail', true)" class="mt-2 text-sm text-red-600 dark:text-red-400 hover:text-red-500 dark:hover:text-red-300">
                                                移除圖片
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- 上傳進度條 -->
                                <div x-show="uploading" class="mt-3">
                                    <div class="bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="bg-primary-600 h-2 rounded-full" :style="`width: ${progress}%`"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('thumbnail') <flux:error :messages="$message" /> @enderror
                    </div>

                    <div class="sm:col-span-6 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2"></div>

                    <!-- 項目鏈接 -->
                    <div class="sm:col-span-3">
                        <flux:label for="url" class="text-gray-700 dark:text-gray-300">項目演示鏈接</flux:label>
                        <flux:input wire:model="url" id="url" type="url" placeholder="https://example.com" class="mt-1 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-500" />
                        @error('url') <flux:error :messages="$message" /> @enderror
                    </div>

                    <!-- GitHub 鏈接 -->
                    <div class="sm:col-span-3">
                        <flux:label for="githubUrl" class="text-gray-700 dark:text-gray-300">GitHub 鏈接</flux:label>
                        <flux:input wire:model="githubUrl" id="githubUrl" type="url" placeholder="https://github.com/username/repo" class="mt-1 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-500" />
                        @error('githubUrl') <flux:error :messages="$message" /> @enderror
                    </div>

                    <!-- 技術標籤 -->
                    <div class="sm:col-span-3 pt-2">
                        <flux:label for="technologies" class="text-gray-700 dark:text-gray-300">使用的技術</flux:label>
                        <flux:input wire:model="technologies" id="technologies" type="text" placeholder="PHP, Laravel, Vue.js, Tailwind CSS" class="mt-1 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-500" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            使用逗號分隔多個技術標籤
                        </p>
                        @error('technologies') <flux:error :messages="$message" /> @enderror
                    </div>

                    <!-- 完成日期 -->
                    <div class="sm:col-span-3 pt-2">
                        <flux:label for="completionDate" class="text-gray-700 dark:text-gray-300">完成日期</flux:label>
                        <flux:input wire:model="completionDate" id="completionDate" type="date" class="mt-1 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        @error('completionDate') <flux:error :messages="$message" /> @enderror
                    </div>

                    <div class="sm:col-span-6 border-t border-gray-200 dark:border-gray-700 pt-4 mt-2"></div>

                    <!-- 特色項目 -->
                    <div class="sm:col-span-3">
                        <flux:checkbox wire:model="isFeatured" id="isFeatured" label="設為特色項目" class="dark:text-gray-300" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            特色項目將在作品集頁面中被突出顯示
                        </p>
                        @error('isFeatured') <flux:error :messages="$message" /> @enderror
                    </div>

                    <!-- 排序順序 -->
                    <div class="sm:col-span-3">
                        <flux:label for="order" class="text-gray-700 dark:text-gray-300">排序順序</flux:label>
                        <flux:input wire:model="order" id="order" type="number" min="0" class="mt-1 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            較小的數字將排在前面
                        </p>
                        @error('order') <flux:error :messages="$message" /> @enderror
                    </div>
                </div>

                <!-- 按鈕區域 -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700 mt-6">
                    <flux:button type="button" wire:click="closeProjectForm" variant="ghost" class="w-full sm:w-auto">
                        取消
                    </flux:button>
                    <flux:button type="submit" variant="primary" class="w-full sm:w-auto">
                        <flux:icon name="check-circle" class="w-5 h-5 mr-2" />
                        {{ $projectId ? '更新項目' : '創建項目' }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>

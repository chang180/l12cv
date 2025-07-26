<div>
    <!-- 項目列表 -->
    @if(count($projects) > 0)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($projects as $project)
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 mr-3">
                                    @if($project->thumbnail)
                                        <img class="h-12 w-12 rounded-md object-cover" src="{{ \Storage::url($project->thumbnail) }}" alt="{{ $project->title }}">
                                    @else
                                        <div class="h-12 w-12 rounded-md bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-base font-medium text-gray-900 dark:text-white">
                                        {{ $project->title }}
                                        @if($project->is_featured)
                                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                特色
                                            </span>
                                        @endif
                                    </h3>
                                    <div class="mt-1 flex flex-wrap gap-2">
                                        @if($project->technologies)
                                            @foreach($project->technologies as $tech)
                                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs rounded-md">
                                                    {{ $tech }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <flux:button wire:click="editProject({{ $project->id }})" variant="ghost" size="sm" class="!px-2">
                                    <flux:icon name="pencil" class="w-4 h-4" />
                                    <span class="sr-only">編輯</span>
                                </flux:button>
                                <flux:button wire:click="confirmDelete({{ $project->id }})" variant="danger" size="sm" class="!px-2">
                                    <flux:icon name="trash" class="w-4 h-4" />
                                    <span class="sr-only">刪除</span>
                                </flux:button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <x-card>
            <div class="py-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">暫無項目</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    您尚未添加任何項目到作品集
                </p>
            </div>
        </x-card>
    @endif

    <div class="flex justify-end">
        <flux:button wire:click="createProject" variant="primary" class="w-full sm:w-auto">
            <flux:icon name="plus-circle" class="w-5 h-5 mr-2" />
            新增項目
        </flux:button>
    </div>

    <!-- 引入項目表單組件 -->
    <livewire:resume.portfolio.project-form :resumeId="$resumeId" />

    <!-- 引入刪除確認對話框組件 -->
    <livewire:resume.portfolio.delete-confirmation />
</div>

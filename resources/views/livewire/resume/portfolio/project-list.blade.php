<div>
    <!-- 項目列表 -->
    @if(count($projects) > 0)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($projects as $project)
            <li class="p-6">
                <div class="flex flex-col sm:flex-row">
                    <!-- 項目縮略圖 - 更大尺寸 -->
                    <div class="flex-shrink-0 w-full sm:w-48 h-48 sm:h-36 mb-4 sm:mb-0 sm:mr-6">
                        @if($project->thumbnail)
                        <img class="w-full h-full rounded-lg object-cover shadow-sm" src="{{ \Storage::url($project->thumbnail) }}" alt="{{ $project->title }}">
                        @else
                        <div class="w-full h-full rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center shadow-sm">
                            <svg class="h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                    </div>

                    <!-- 項目詳細信息 -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                                    {{ $project->title }}
                                    @if($project->is_featured)
                                    <span class="ml-2 px-2 py-1 inline-flex text-xs leading-4 font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        <flux:icon name="star" class="w-3 h-3 mr-1" /> 特色
                                    </span>
                                    @endif
                                </h3>

                                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                    @if($project->completion_date)
                                    <span>
                                        完成日期: {{ \Carbon\Carbon::parse($project->completion_date)->format('Y年m月') }}
                                    </span>
                                    @endif
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ $project->views ?? 0 }} 瀏覽
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 ml-4">
                                <flux:button wire:click="editProject({{ $project->id }})" variant="ghost" size="sm" class="!p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                                    <flux:icon name="pencil" class="w-4 h-4" />
                                    <span class="sr-only">編輯</span>
                                </flux:button>
                                <flux:button wire:click="confirmDelete({{ $project->id }})" variant="danger" size="sm" class="!p-2 rounded-full hover:bg-red-100 dark:hover:bg-red-900">
                                    <flux:icon name="trash" class="w-4 h-4" />
                                    <span class="sr-only">刪除</span>
                                </flux:button>
                            </div>
                        </div>

                        @if($project->description)
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                            {{ $project->description }}
                        </p>
                        @endif

                        <div class="mt-3 flex flex-wrap gap-2">
                            @if($project->technologies)
                            @foreach($project->technologies as $tech)
                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs rounded-md">
                                {{ $tech }}
                            </span>
                            @endforeach
                            @endif
                        </div>

                        <div class="mt-4 flex items-center space-x-4">
                            @if($project->url)
                            <a href="{{ $project->url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-sm text-primary-600 dark:text-primary-400 hover:text-primary-500 dark:hover:text-primary-300">
                                <flux:icon name="globe-alt" class="w-4 h-4 mr-1" />
                                演示鏈接
                            </a>
                            @endif

                            @if($project->github_url)
                            <a href="{{ $project->github_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.342-3.369-1.342-.454-1.155-1.11-1.462-1.11-1.462-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.202 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.163 22 16.418 22 12c0-5.523-4.477-10-10-10z" />
                                </svg>
                                GitHub
                            </a>
                            @endif

                            @if($project->order !== null)
                            <span class="inline-flex items-center text-xs text-gray-500 dark:text-gray-400">
                                <flux:icon name="arrow-up" class="w-4 h-4 mr-1" />
                                排序: {{ $project->order }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @else
    <x-card>
        <div class="py-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">暫無項目</h3>
            <p class="mt-2 text-base text-gray-500 dark:text-gray-400">
                您尚未添加任何項目到作品集
            </p>
            <div class="mt-6">
                <button 
                    wire:click="createProject" 
                    class="bg-gradient-to-r from-indigo-500 to-pink-600 hover:from-indigo-600 hover:to-pink-700 active:scale-95 text-white font-semibold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-3 mx-auto group"
                >
                    <div class="p-2 bg-white/20 rounded-lg group-hover:bg-white/30 transition-colors">
                        <i class="fas fa-plus text-sm"></i>
                    </div>
                    <span>添加您的第一個項目</span>
                </button>
            </div>
        </div>
    </x-card>
    @endif

    <div class="flex justify-end mt-6">
        <button 
            wire:click="createProject" 
            class="bg-gradient-to-r from-indigo-500 to-pink-600 hover:from-indigo-600 hover:to-pink-700 active:scale-95 text-white font-semibold px-8 py-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-3 w-full sm:w-auto min-w-[160px] group"
        >
            <div class="p-2 bg-white/20 rounded-lg group-hover:bg-white/30 transition-colors">
                <i class="fas fa-plus text-sm"></i>
            </div>
            <span>新增項目</span>
        </button>
    </div>

    <!-- 引入項目表單組件 -->
    <livewire:resume.portfolio.project-form :resumeId="$resumeId" />

    <!-- 引入刪除確認對話框組件 -->
    <livewire:resume.portfolio.delete-confirmation />
</div>

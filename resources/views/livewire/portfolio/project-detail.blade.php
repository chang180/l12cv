<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->title }} - {{ $user->name }} 的作品</title>

    @fluxAppearance

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Styles -->
    @livewireStyles
</head>

<body>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="py-8">
            {{-- 頂部導航區域 --}}
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                <div class="flex items-center justify-between">
                    <a href="{{ route('portfolio.public', $user->id) }}"
                        class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24"
                            height="24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        返回作品集
                    </a>

                    @if($resume)
                    <div class="flex space-x-4">
                        <a href="{{ route('resume.public', $resume->slug) }}"
                           class="bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            履歷
                        </a>
                        <a href="{{ route('portfolio.public', $user->id) }}"
                           class="bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300 px-4 py-2 rounded-md text-sm font-medium">
                            作品集
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- 作品詳情卡片 --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden mb-8">
                    @if($project->thumbnail)
                        <img src="{{ Storage::url($project->thumbnail) }}"
                             alt="{{ $project->title }}"
                             class="w-full h-64 object-cover">
                    @else
                        <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->title }}</h1>

                            @if($project->completion_date)
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $project->getFormattedCompletionDate('Y年m月') }}
                                </span>
                            @endif
                        </div>

                        @if($project->technologies)
                            <div class="flex flex-wrap gap-2 mb-6">
                                @foreach($project->technologies as $tech)
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm rounded-md">{{ $tech }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="prose prose-primary dark:prose-invert max-w-none mb-6">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $project->description }}</p>
                        </div>

                        <div class="flex space-x-4 mt-6">
                            @if($project->url)
                                <a href="{{ $project->url }}" target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:border-primary-700 focus:ring focus:ring-primary-300 disabled:opacity-25 transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    查看演示
                                </a>
                            @endif

                            @if($project->github_url)
                                <a href="{{ $project->github_url }}" target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                    </svg>
                                    GitHub
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- 作者信息 --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">關於作者</h2>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if ($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}"
                                     alt="{{ $user->name }}"
                                     class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center">
                                    <span class="text-lg font-bold text-primary-600 dark:text-primary-400">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h3 class="text-md font-medium text-gray-900 dark:text-white">{{ $user->name }}</h3>
                            <a href="{{ route('portfolio.public', $user->id) }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 text-sm">
                                查看所有作品
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewireScripts
</body>

</html>

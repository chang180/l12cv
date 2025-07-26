<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - 作品集</title>

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
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24"
                            height="24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        返回首頁
                    </a>

                    @if($resume)
                    <div class="flex space-x-4">
                        <a href="{{ route('resume.public', $resume->slug) }}"
                            class="bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            履歷
                        </a>
                        <span class="bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300 px-4 py-2 rounded-md text-sm font-medium">
                            作品集
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- 用戶資訊卡片 --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden mb-8">
                    <div class="p-6">
                        <div class="flex items-start space-x-6">
                            <div class="flex-shrink-0">
                                @if ($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}"
                                    alt="{{ $user->name }}"
                                    class="w-24 h-24 rounded-full object-cover">
                                @else
                                <div class="w-24 h-24 rounded-full bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center">
                                    <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                    {{ $user->name }}
                                </h1>
                                <h2 class="text-xl text-primary-600 dark:text-primary-400 mb-4">
                                    作品集
                                </h2>
                                <p class="text-gray-600 dark:text-gray-300">
                                    這裡展示了我的專案作品，展現我的技能和經驗。
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if(count($projects) > 0)
                {{-- 作品集網格 --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                    <a href="{{ route('portfolio.project.detail', ['user' => $user->id, 'project' => $project->id]) }}" class="block">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
                            @if($project->thumbnail)
                            <img src="{{ Storage::url($project->thumbnail) }}"
                                alt="{{ $project->title }}"
                                class="w-full h-48 object-cover">
                            @else
                            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif

                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $project->title }}</h3>

                                @if($project->technologies)
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($project->technologies as $tech)
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs rounded-md">{{ $tech }}</span>
                                    @endforeach
                                </div>
                                @endif

                                <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3 mb-4">{{ $project->description }}</p>

                                <div class="flex flex-wrap gap-3 justify-between">
                                    @if($project->url)
                                    <span onclick="event.stopPropagation(); window.open('{{ $project->url }}', '_blank')"
                                        class="inline-flex items-center text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium cursor-pointer">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        查看演示
                                    </span>
                                    @endif

                                    @if($project->github_url)
                                    <span onclick="event.stopPropagation(); window.open('{{ $project->github_url }}', '_blank')"
                                        class="inline-flex items-center text-gray-600 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300 text-sm font-medium cursor-pointer">
                                        <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.342-3.369-1.342-.454-1.155-1.11-1.462-1.11-1.462-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.202 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.163 22 16.418 22 12c0-5.523-4.477-10-10-10z" />
                                        </svg>
                                        GitHub
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                {{-- 無作品時顯示 --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">暫無作品</h3>
                        <p class="text-gray-500 dark:text-gray-400">該用戶尚未添加任何作品</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @livewireScripts
</body>

</html>

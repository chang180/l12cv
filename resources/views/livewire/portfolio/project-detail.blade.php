<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->title }} - {{ $user->name }} 的作品</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @fluxAppearance

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Styles -->
    @livewireStyles
</head>

<body>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- 頂部導航區域 -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-4">
                    <!-- 左側：返回按鈕和品牌 -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('portfolio.public', $user->slug) }}"
                            class="inline-flex items-center text-sm text-gray-600 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            返回作品集
                        </a>
                        
                        <div class="hidden sm:flex items-center space-x-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-purple-600 to-pink-600">
                                <i class="fas fa-folder-open text-white text-sm"></i>
                            </div>
                            <span class="font-bold text-gray-900 dark:text-white">項目詳情</span>
                        </div>
                    </div>

                    <!-- 右側：導航和 Dark Mode -->
                    <div class="flex items-center space-x-4">
                        @if($resume)
                        <div class="hidden sm:flex space-x-3">
                            <a href="{{ route('resume.public', $resume->slug) }}"
                               class="bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md">
                                <i class="fas fa-user mr-2"></i>履歷
                            </a>
                            <a href="{{ route('portfolio.public', $user->slug) }}"
                               class="bg-gradient-to-r from-purple-500 to-pink-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md">
                                <i class="fas fa-folder mr-2"></i>作品集
                            </a>
                        </div>
                        @endif
                        
                        <!-- Dark Mode 切換 -->
                        <div x-data="{ 
                            isDark: document.documentElement.classList.contains('dark'),
                            toggleTheme() {
                                this.isDark = window.DarkModeManager.toggle() === 'dark';
                            }
                        }">
                            <button 
                                @click="toggleTheme()"
                                class="relative inline-flex h-10 w-20 items-center rounded-full bg-gray-200 dark:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 border-2 border-gray-300 dark:border-gray-600"
                            >
                                <span class="sr-only">切換深色模式</span>
                                <!-- 太陽圖標 (左側) -->
                                <i class="fas fa-sun absolute left-2 text-yellow-500 text-sm" 
                                   :class="isDark ? 'opacity-50' : 'opacity-100'"></i>
                                <!-- 月亮圖標 (右側) -->
                                <i class="fas fa-moon absolute right-2 text-purple-600 text-sm"
                                   :class="isDark ? 'opacity-100' : 'opacity-50'"></i>
                                <!-- 滑動圓球 -->
                                <span 
                                    :class="isDark ? 'translate-x-10' : 'translate-x-1'"
                                    class="inline-block h-7 w-7 transform rounded-full bg-white dark:bg-gray-800 shadow-lg transition-transform duration-300 flex items-center justify-center border border-gray-200 dark:border-gray-600"
                                >
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-8">

            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- 項目主圖區域 -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mb-6 sm:mb-8 border border-gray-100 dark:border-gray-700">
                    <!-- 主圖 -->
                    <div class="relative">
                        @if($project->thumbnail)
                            <img src="{{ Storage::url($project->thumbnail) }}"
                                 alt="{{ $project->title }}"
                                 class="w-full h-64 sm:h-96 object-cover">
                        @else
                            <div class="w-full h-64 sm:h-96 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/50 dark:to-pink-900/50 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-image text-4xl sm:text-6xl text-purple-400 dark:text-purple-300 mb-2 sm:mb-4"></i>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm sm:text-base">暫無項目圖片</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- 圖片覆蓋層 -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                        
                        <!-- 項目標題覆蓋層 -->
                        <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-8">
                            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between space-y-3 sm:space-y-0">
                                <div>
                                    <h1 class="text-2xl sm:text-4xl font-bold text-white mb-2">{{ $project->title }}</h1>
                                    @if($project->completion_date)
                                        <div class="flex items-center text-white/80 text-sm sm:text-base">
                                            <i class="fas fa-calendar mr-2"></i>
                                            <span>{{ $project->getFormattedCompletionDate('Y年m月') }} 完成</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- 項目統計 -->
                                <div class="text-left sm:text-right text-white/80">
                                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm">
                                        <div class="flex items-center">
                                            <i class="fas fa-eye mr-1"></i>
                                            <span>{{ $project->views ?? 0 }} 瀏覽</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-clock mr-1"></i>
                                            <span>{{ $project->created_at->format('Y/m/d') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 項目內容 -->
                    <div class="p-4 sm:p-8">

                        <!-- 技術標籤 -->
                        @if($project->technologies)
                            <div class="mb-6 sm:mb-8">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-3 sm:mb-4 flex items-center">
                                    <i class="fas fa-code mr-2 text-purple-600 dark:text-purple-400 text-sm sm:text-base"></i>
                                    技術棧
                                </h3>
                                <div class="flex flex-wrap gap-2 sm:gap-3">
                                    @foreach($project->technologies as $tech)
                                        <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/50 dark:to-pink-900/50 text-purple-700 dark:text-purple-300 text-xs sm:text-sm font-medium rounded-full border border-purple-200 dark:border-purple-800 hover:from-purple-200 hover:to-pink-200 dark:hover:from-purple-800/70 dark:hover:to-pink-800/70 transition-all duration-200">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- 項目描述 -->
                        <div class="mb-6 sm:mb-8">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-white mb-3 sm:mb-4 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-blue-600 dark:text-blue-400 text-sm sm:text-base"></i>
                                項目描述
                            </h3>
                            <div class="prose prose-gray dark:prose-invert max-w-none">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed text-sm sm:text-lg">{{ $project->description }}</p>
                            </div>
                        </div>

                        <!-- 項目連結 -->
                        <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
                            @if($project->url)
                                <a href="{{ $project->url }}" target="_blank"
                                   class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                                    <i class="fas fa-external-link-alt mr-2 sm:mr-3 text-sm sm:text-base"></i>
                                    <span class="text-sm sm:text-base">查看演示</span>
                                </a>
                            @endif

                            @if($project->github_url)
                                <a href="{{ $project->github_url }}" target="_blank"
                                   class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-800 hover:to-gray-900 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                                    <i class="fab fa-github mr-2 sm:mr-3 text-sm sm:text-base"></i>
                                    <span class="text-sm sm:text-base">GitHub</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 作者資訊 -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-4 sm:p-8 border border-gray-100 dark:border-gray-700">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-6 flex items-center justify-center sm:justify-start">
                        <i class="fas fa-user mr-2 sm:mr-3 text-purple-600 dark:text-purple-400 text-lg sm:text-xl"></i>
                        關於作者
                    </h2>
                    <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                        <div class="flex-shrink-0 mx-auto sm:mx-0">
                            @if ($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}"
                                     alt="{{ $user->name }}"
                                     class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-4 border-purple-200 dark:border-purple-800 shadow-lg">
                            @else
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/50 dark:to-pink-900/50 flex items-center justify-center border-4 border-purple-200 dark:border-purple-800 shadow-lg">
                                    <span class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $user->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm sm:text-base">創意開發者</p>
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                                <a href="{{ route('portfolio.public', $user->slug) }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-folder-open mr-2 text-sm sm:text-base"></i>
                                    <span class="text-sm sm:text-base">查看所有作品</span>
                                </a>
                                @if($resume)
                                <a href="{{ route('resume.public', $resume->slug) }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-user mr-2 text-sm sm:text-base"></i>
                                    <span class="text-sm sm:text-base">查看履歷</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- 底部版權 -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-6 sm:py-8 mt-12 sm:mt-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center space-x-2 sm:space-x-3 mb-3 md:mb-0">
                        <div class="flex h-6 w-6 sm:h-8 sm:w-8 items-center justify-center rounded-lg bg-gradient-to-r from-purple-600 to-pink-600">
                            <i class="fas fa-folder-open text-white text-xs sm:text-sm"></i>
                        </div>
                        <span class="font-bold text-gray-900 dark:text-white text-sm sm:text-base">L12CV</span>
                        <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 hidden sm:block">作品集平台</span>
                    </div>
                    <div class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 text-center md:text-right">
                        © {{ date('Y') }} L12CV. 展示您的創意作品
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Dark Mode 腳本 -->
    @vite('resources/js/dark-mode.js')
    <!-- Alpine.js 腳本 -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireScripts
</body>

</html>

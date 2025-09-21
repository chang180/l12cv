<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - 作品集</title>
    
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
            <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">
                <!-- 手機版佈局 -->
                <div class="md:hidden">
                    <!-- 手機版第一行：品牌和右側按鈕 -->
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center space-x-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-r from-purple-600 to-pink-600">
                                <i class="fas fa-folder-open text-white text-sm"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-sm text-gray-900 dark:text-white">L12CV</span>
                            </div>
                        </div>
                        
                        <!-- 手機版右側按鈕 -->
                        <div class="flex items-center space-x-2">
                            <div x-data="{ 
                                isDark: document.documentElement.classList.contains('dark'),
                                toggleTheme() {
                                    this.isDark = window.DarkModeManager.toggle() === 'dark';
                                }
                            }">
                                <button 
                                    @click="toggleTheme()"
                                    class="relative inline-flex h-7 w-14 items-center rounded-full bg-gray-200 dark:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 border border-gray-300 dark:border-gray-600"
                                >
                                    <span class="sr-only">切換深色模式</span>
                                    <i class="fas fa-sun absolute left-1 text-yellow-500 text-xs" 
                                       :class="isDark ? 'opacity-50' : 'opacity-100'"></i>
                                    <i class="fas fa-moon absolute right-1 text-purple-600 text-xs"
                                       :class="isDark ? 'opacity-100' : 'opacity-50'"></i>
                                    <span 
                                        :class="isDark ? 'translate-x-7' : 'translate-x-0.5'"
                                        class="inline-block h-5 w-5 transform rounded-full bg-white dark:bg-gray-800 shadow-md transition-transform duration-300 flex items-center justify-center border border-gray-200 dark:border-gray-600"
                                    >
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 手機版第二行：導航按鈕 -->
                    <div class="pb-3 pt-2 border-t border-gray-200/50 dark:border-gray-700/50">
                        <div class="flex justify-center space-x-3">
                            @if($resume)
                            <a href="{{ route('resume.public', $resume->slug) }}"
                                class="bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md flex items-center">
                                <i class="fas fa-user mr-2"></i>履歷
                            </a>
                            @endif
                            <span class="bg-gradient-to-r from-purple-500 to-pink-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md flex items-center">
                                <i class="fas fa-folder mr-2"></i>作品集
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- 桌面版佈局 -->
                <div class="hidden md:flex items-center justify-between py-3 sm:py-4">
                    <!-- 左側：品牌和導航 -->
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-r from-purple-600 to-pink-600">
                                <i class="fas fa-folder-open text-white text-lg"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-lg text-gray-900 dark:text-white">L12CV</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">作品集平台</span>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            @if($resume)
                            <a href="{{ route('resume.public', $resume->slug) }}"
                                class="bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md">
                                <i class="fas fa-user mr-2"></i>履歷
                            </a>
                            @endif
                            <span class="bg-gradient-to-r from-purple-500 to-pink-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md">
                                <i class="fas fa-folder mr-2"></i>作品集
                            </span>
                        </div>
                    </div>
                    
                    <!-- 右側：Dark Mode 切換 -->
                    <div class="flex items-center space-x-4">
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
                                <i class="fas fa-sun absolute left-2 text-yellow-500 text-sm" 
                                   :class="isDark ? 'opacity-50' : 'opacity-100'"></i>
                                <i class="fas fa-moon absolute right-2 text-purple-600 text-sm"
                                   :class="isDark ? 'opacity-100' : 'opacity-50'"></i>
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
                <!-- 用戶資訊卡片 -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mb-8 border border-gray-100 dark:border-gray-700">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-600 p-6">
                        <div class="flex items-start space-x-6">
                            <div class="flex-shrink-0">
                                @if ($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}"
                                    alt="{{ $user->name }}"
                                    class="w-28 h-28 rounded-full object-cover border-4 border-white/20 shadow-lg">
                                @else
                                <div class="w-28 h-28 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white/20 shadow-lg">
                                    <span class="text-4xl font-bold text-white">
                                        {{ substr($user->name, 0, 1) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0 text-white">
                                <h1 class="text-3xl font-bold mb-2">
                                    {{ $user->name }}
                                </h1>
                                <h2 class="text-xl font-medium mb-4 text-white/90">
                                    作品集
                                </h2>
                                <p class="text-white/80 leading-relaxed">
                                    這裡展示了我的專案作品，展現我的技能和經驗。
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- 統計資訊 -->
                    <div class="p-6 bg-gray-50 dark:bg-gray-700/50">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-2 rounded-full bg-purple-100 dark:bg-purple-900/50">
                                    <i class="fas fa-folder-open text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($projects) }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">專案作品</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-2 rounded-full bg-pink-100 dark:bg-pink-900/50">
                                    <i class="fas fa-code text-pink-600 dark:text-pink-400"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ collect($projects)->pluck('technologies')->flatten()->unique()->count() }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">技術棧</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-2 rounded-full bg-indigo-100 dark:bg-indigo-900/50">
                                    <i class="fas fa-star text-indigo-600 dark:text-indigo-400"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ collect($projects)->sum('views') }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">總瀏覽數</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(count($projects) > 0)
                <!-- 作品集網格 -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($projects as $project)
                    <a href="{{ route('portfolio.project.detail', ['slug' => $user->slug, 'project' => $project->id]) }}" class="group block">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                            <!-- 項目縮圖 -->
                            <div class="relative overflow-hidden">
                                @if($project->thumbnail)
                                <img src="{{ Storage::url($project->thumbnail) }}"
                                    alt="{{ $project->title }}"
                                    class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                <div class="w-full h-56 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/50 dark:to-pink-900/50 flex items-center justify-center">
                                    <i class="fas fa-image text-4xl text-purple-400 dark:text-purple-300"></i>
                                </div>
                                @endif
                                
                                <!-- 懸停效果覆蓋層 -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4">
                                    <div class="text-white text-center">
                                        <i class="fas fa-external-link-alt text-2xl mb-2"></i>
                                        <p class="text-sm font-medium">查看詳情</p>
                                    </div>
                                </div>
                            </div>

                            <!-- 項目內容 -->
                            <div class="p-6">
                                <!-- 項目標題 -->
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors duration-200">
                                    {{ $project->title }}
                                </h3>

                                <!-- 技術標籤 -->
                                @if($project->technologies)
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach(array_slice($project->technologies, 0, 4) as $tech)
                                    <span class="px-3 py-1 bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/50 dark:to-pink-900/50 text-purple-700 dark:text-purple-300 text-xs font-medium rounded-full border border-purple-200 dark:border-purple-800">
                                        {{ $tech }}
                                    </span>
                                    @endforeach
                                    @if(count($project->technologies) > 4)
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                        +{{ count($project->technologies) - 4 }}
                                    </span>
                                    @endif
                                </div>
                                @endif

                                <!-- 項目描述 -->
                                <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed line-clamp-3 mb-4">
                                    {{ $project->description }}
                                </p>

                                <!-- 項目統計 -->
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-eye"></i>
                                        <span>{{ $project->views ?? 0 }} 瀏覽</span>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <i class="fas fa-calendar"></i>
                                        <span>{{ $project->created_at->format('Y/m/d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <!-- 無作品時顯示 -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-12 text-center border border-gray-100 dark:border-gray-700">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/50 dark:to-pink-900/50 rounded-full flex items-center justify-center">
                        <i class="fas fa-folder-open text-purple-600 dark:text-purple-400 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">暫無作品</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-lg mb-6">該用戶尚未添加任何作品</p>
                    <div class="text-sm text-gray-400 dark:text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        作品集功能正在開發中
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- 底部版權 -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-8 mt-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center space-x-3 mb-4 md:mb-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-purple-600 to-pink-600">
                            <i class="fas fa-folder-open text-white text-sm"></i>
                        </div>
                        <span class="font-bold text-gray-900 dark:text-white">L12CV</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">作品集平台</span>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
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

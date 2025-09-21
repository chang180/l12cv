<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resume->user->name ?? '未知' }} - 履歷</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @fluxAppearance

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Styles -->
    @livewireStyles
</head>

<body>
    <?php

    use function Livewire\Volt\{state};

    state(['resume']);
    ?>

    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- 頂部導航區域 -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-4">
                    <!-- 左側：品牌和導航 -->
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-purple-600">
                                <i class="fas fa-file-alt text-white text-lg"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-lg text-gray-900 dark:text-white">L12CV</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">履歷平台</span>
                            </div>
                        </div>
                        
                        <div class="hidden sm:flex space-x-3">
                            <span class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md">
                                <i class="fas fa-user mr-2"></i>履歷
                            </span>
                            <a href="{{ route('portfolio.public', $resume->user->slug) }}"
                                class="bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md">
                                <i class="fas fa-folder mr-2"></i>作品集
                            </a>
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
                                class="relative inline-flex h-10 w-20 items-center rounded-full bg-gray-200 dark:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 border-2 border-gray-300 dark:border-gray-600"
                            >
                                <span class="sr-only">切換深色模式</span>
                                <!-- 太陽圖標 (左側) -->
                                <i class="fas fa-sun absolute left-2 text-yellow-500 text-sm" 
                                   :class="isDark ? 'opacity-50' : 'opacity-100'"></i>
                                <!-- 月亮圖標 (右側) -->
                                <i class="fas fa-moon absolute right-2 text-blue-600 text-sm"
                                   :class="isDark ? 'opacity-100' : 'opacity-50'"></i>
                                <!-- 滑動圓球 -->
                                <span 
                                    :class="isDark ? 'translate-x-10' : 'translate-x-1'"
                                    class="inline-block h-7 w-7 transform rounded-full bg-white dark:bg-gray-800 shadow-lg transition-transform duration-300 flex items-center justify-center border border-gray-200 dark:border-gray-600"
                                >
                                </span>
                            </button>
                        </div>
                        
                        <!-- 下載 PDF 按鈕 -->
                        <a 
                            href="{{ route('resume.pdf', ['slug' => $resume->slug]) }}"
                            target="_blank"
                            class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2"
                        >
                            <i class="fas fa-download"></i>
                            <span class="hidden sm:inline">下載 PDF</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-8">

            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                @if ($resume)
                <!-- 個人資料卡片 -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mb-8 border border-gray-100 dark:border-gray-700">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6">
                        <div class="flex items-start space-x-6">
                            <div class="flex-shrink-0">
                                @if ($resume->user && $resume->user->avatar)
                                <img src="{{ Storage::url($resume->user->avatar) }}"
                                    alt="{{ $resume->user->name ?? '未知' }}"
                                    class="w-28 h-28 rounded-full object-cover border-4 border-white/20 shadow-lg">
                                @else
                                <div class="w-28 h-28 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white/20 shadow-lg">
                                    <span class="text-4xl font-bold text-white">
                                        {{ substr($resume->user->name ?? '未知', 0, 1) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0 text-white">
                                <h1 class="text-3xl font-bold mb-2">
                                    {{ $resume->user->name ?? '未知' }}
                                </h1>
                                <h2 class="text-xl font-medium mb-4 text-white/90">
                                    {{ $resume->title }}
                                </h2>
                                @if ($resume->summary)
                                <p class="text-white/80 whitespace-pre-line leading-relaxed">
                                    {{ $resume->summary }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- 統計資訊 -->
                    <div class="p-6 bg-gray-50 dark:bg-gray-700/50">
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                            <div class="text-center">
                                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-2 rounded-full bg-blue-100 dark:bg-blue-900/50">
                                    <i class="fas fa-graduation-cap text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($resume->education ?? []) }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">學歷背景</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-2 rounded-full bg-green-100 dark:bg-green-900/50">
                                    <i class="fas fa-briefcase text-green-600 dark:text-green-400"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($resume->experience ?? []) }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">工作經驗</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-2 rounded-full bg-orange-100 dark:bg-orange-900/50">
                                    <i class="fas fa-eye text-orange-600 dark:text-orange-400"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $resume->views ?? 0 }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">瀏覽次數</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-2 rounded-full bg-purple-100 dark:bg-purple-900/50">
                                    <i class="fas fa-calendar text-purple-600 dark:text-purple-400"></i>
                                </div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ abs(round(now()->diffInDays($resume->created_at))) }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">天前建立</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 學歷背景 -->
                @if (!empty($resume->education))
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mb-8 border border-gray-100 dark:border-gray-700">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-graduation-cap mr-3"></i>
                            學歷背景
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach ($resume->education as $index => $edu)
                            <div class="relative pb-6 last:pb-0">
                                <!-- 時間軸線 -->
                                @if($index < count($resume->education) - 1)
                                <div class="absolute left-6 top-12 w-0.5 h-full bg-gradient-to-b from-blue-200 to-blue-100 dark:from-blue-800 dark:to-blue-900"></div>
                                @endif
                                
                                <div class="flex items-start space-x-4">
                                    <!-- 時間軸點 -->
                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-university text-white text-sm"></i>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-100 dark:border-gray-600">
                                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3">
                                                <div>
                                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                                        {{ $edu['school'] }}
                                                    </h3>
                                                    <div class="text-blue-600 dark:text-blue-400 font-medium text-sm">
                                                        {{ $edu['degree'] }} · {{ $edu['field'] }}
                                                    </div>
                                                </div>
                                                <div class="mt-2 sm:mt-0">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        {{ $edu['start_date'] }} - {{ $edu['end_date'] }}
                                                    </span>
                                                </div>
                                            </div>
                                            @if (!empty($edu['description']))
                                            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                                                {{ $edu['description'] }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- 工作經驗 -->
                @if (!empty($resume->experience))
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden mb-8 border border-gray-100 dark:border-gray-700">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-briefcase mr-3"></i>
                            工作經驗
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach ($resume->experience as $index => $exp)
                            <div class="relative pb-6 last:pb-0">
                                <!-- 時間軸線 -->
                                @if($index < count($resume->experience) - 1)
                                <div class="absolute left-6 top-12 w-0.5 h-full bg-gradient-to-b from-green-200 to-green-100 dark:from-green-800 dark:to-green-900"></div>
                                @endif
                                
                                <div class="flex items-start space-x-4">
                                    <!-- 時間軸點 -->
                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center shadow-lg">
                                        @if($exp['current'] ?? false)
                                            <i class="fas fa-play text-white text-sm"></i>
                                        @else
                                            <i class="fas fa-building text-white text-sm"></i>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-100 dark:border-gray-600">
                                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3">
                                                <div>
                                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                                        {{ $exp['position'] }}
                                                    </h3>
                                                    <div class="text-green-600 dark:text-green-400 font-medium text-sm">
                                                        {{ $exp['company'] }}
                                                    </div>
                                                </div>
                                                <div class="mt-2 sm:mt-0 flex flex-col items-end space-y-1">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        {{ $exp['start_date'] }} - {{ $exp['current'] ? '至今' : $exp['end_date'] }}
                                                    </span>
                                                    @if($exp['current'] ?? false)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                                        <i class="fas fa-circle mr-1 text-xs"></i>
                                                        目前在職
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if (!empty($exp['description']))
                                            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed whitespace-pre-line">
                                                {{ $exp['description'] }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                </div>
                @else
                <!-- 錯誤頁面 -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-12 text-center border border-gray-100 dark:border-gray-700">
                    <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-red-100 to-pink-100 dark:from-red-900/50 dark:to-pink-900/50 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">找不到此履歷</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-lg mb-6">此履歷可能不存在或未公開</p>
                    <a href="/" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-home mr-2"></i>
                        返回首頁
                    </a>
                </div>
                @endif
            </div>
        </div>
        
        <!-- 底部版權 -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-8 mt-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center space-x-3 mb-4 md:mb-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-purple-600">
                            <i class="fas fa-file-alt text-white text-sm"></i>
                        </div>
                        <span class="font-bold text-gray-900 dark:text-white">L12CV</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">履歷平台</span>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        © {{ date('Y') }} L12CV. 讓您的履歷更專業
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

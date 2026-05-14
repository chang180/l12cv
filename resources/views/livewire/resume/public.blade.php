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

    @php
        $template = \App\Support\ResumeTemplates::resolve($resume->template ?? null);
        $templateClasses = \App\Support\ResumeTemplates::publicClasses($template['key']);
        $resumeProjects = $resume->user?->projects?->take(3) ?? collect();
    @endphp

    <div class="min-h-screen {{ $templateClasses['page'] }} print-page" data-resume-template="{{ $template['key'] }}">
        <!-- 頂部導航區域 -->
        <div class="print-hide bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10">
            <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8">
                <!-- 手機版佈局 -->
                <div class="md:hidden">
                    <!-- 手機版第一行：品牌和右側按鈕 -->
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center space-x-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-purple-600">
                                <i class="fas fa-file-alt text-white text-sm"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-sm text-gray-900 dark:text-white">L13CV</span>
                            </div>
                        </div>
                        
                        <!-- 手機版右側按鈕 -->
                        <div class="flex items-center space-x-2">
                        <button 
                            type="button"
                            x-data="{ 
                                toggleTheme() { 
                                    if ($flux.appearance === 'light') { 
                                        $flux.appearance = 'dark' 
                                    } else { 
                                        $flux.appearance = 'light' 
                                    } 
                                }
                            }"
                            @click="toggleTheme()"
                            class="group relative p-2 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-200 border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 shadow-sm hover:shadow-md"
                            aria-label="切換主題"
                        >
                            <svg x-show="$flux.appearance === 'light'" class="w-4 h-4 text-slate-600 dark:text-slate-300 transition-all duration-200 group-hover:text-slate-800 dark:group-hover:text-slate-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <svg x-show="$flux.appearance === 'dark'" class="w-4 h-4 text-slate-600 dark:text-slate-300 transition-all duration-200 group-hover:text-slate-800 dark:group-hover:text-slate-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg x-show="$flux.appearance === 'system'" class="w-4 h-4 text-slate-600 dark:text-slate-300 transition-all duration-200 group-hover:text-slate-800 dark:group-hover:text-slate-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                            
                            <a 
                                href="{{ route('resume.pdf', ['slug' => $resume->slug]) }}"
                                target="_blank"
                                class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-2 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 shadow-md hover:shadow-lg flex items-center"
                            >
                                <i class="fas fa-download text-xs"></i>
                            </a>
                            <button
                                type="button"
                                onclick="window.print()"
                                class="bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 px-2 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 shadow-md hover:shadow-lg flex items-center"
                                aria-label="列印履歷"
                            >
                                <i class="fas fa-print text-xs"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- 手機版第二行：導航按鈕 -->
                    <div class="pb-3 pt-2 border-t border-gray-200/50 dark:border-gray-700/50">
                        <div class="flex justify-center space-x-3">
                            <span class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md flex items-center">
                                <i class="fas fa-user mr-2"></i>履歷
                            </span>
                            <a href="{{ route('portfolio.public', $resume->user->slug) }}"
                                class="bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md flex items-center">
                                <i class="fas fa-folder mr-2"></i>作品集
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- 桌面版佈局 -->
                <div class="hidden md:flex items-center justify-between py-3 sm:py-4">
                    <!-- 左側：品牌和導航 -->
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-r from-blue-600 to-purple-600">
                                <i class="fas fa-file-alt text-white text-lg"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-lg text-gray-900 dark:text-white">L13CV</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">履歷平台</span>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
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
                        <button 
                            type="button"
                            x-data="{ 
                                toggleTheme() { 
                                    if ($flux.appearance === 'light') { 
                                        $flux.appearance = 'dark' 
                                    } else { 
                                        $flux.appearance = 'light' 
                                    } 
                                }
                            }"
                            @click="toggleTheme()"
                            class="group relative p-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-200 border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 shadow-sm hover:shadow-md"
                            aria-label="切換主題"
                        >
                            <svg x-show="$flux.appearance === 'light'" class="w-5 h-5 text-slate-600 dark:text-slate-300 transition-all duration-200 group-hover:text-slate-800 dark:group-hover:text-slate-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <svg x-show="$flux.appearance === 'dark'" class="w-5 h-5 text-slate-600 dark:text-slate-300 transition-all duration-200 group-hover:text-slate-800 dark:group-hover:text-slate-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg x-show="$flux.appearance === 'system'" class="w-5 h-5 text-slate-600 dark:text-slate-300 transition-all duration-200 group-hover:text-slate-800 dark:group-hover:text-slate-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                        
                        <a 
                            href="{{ route('resume.pdf', ['slug' => $resume->slug]) }}"
                            target="_blank"
                            class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2"
                        >
                            <i class="fas fa-download"></i>
                            <span>下載 PDF</span>
                        </a>
                        <button
                            type="button"
                            onclick="window.print()"
                            class="bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2"
                        >
                            <i class="fas fa-print"></i>
                            <span>列印</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-8">

            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                @if ($resume)
                <!-- 個人資料卡片 -->
                <div class="print-card bg-white dark:bg-gray-800 {{ $templateClasses['card'] }} shadow-xl overflow-hidden {{ $templateClasses['spacing'] }} border border-gray-100 dark:border-gray-700">
                    <div class="bg-gradient-to-r {{ $templateClasses['hero'] }} p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
                            <div class="flex-shrink-0 mx-auto sm:mx-0">
                                @if ($resume->user && $resume->user->avatar)
                                <img src="{{ Storage::url($resume->user->avatar) }}"
                                    alt="{{ $resume->user->name ?? '未知' }}"
                                    class="w-20 h-20 sm:w-28 sm:h-28 rounded-full object-cover border-4 border-white/20 shadow-lg">
                                @else
                                <div class="w-20 h-20 sm:w-28 sm:h-28 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white/20 shadow-lg">
                                    <span class="text-2xl sm:text-4xl font-bold text-white">
                                        {{ substr($resume->user->name ?? '未知', 0, 1) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0 text-white">
                                <h1 class="text-2xl sm:text-3xl font-bold mb-2">
                                    {{ $resume->user->name ?? '未知' }}
                                </h1>
                                <h2 class="text-lg sm:text-xl font-medium mb-3 sm:mb-4 text-white/90">
                                    {{ $resume->title }}
                                </h2>
                                @if ($resume->summary)
                                <div class="text-sm sm:text-base text-white/80 leading-relaxed prose prose-invert max-w-none">
                                    {!! \App\Helpers\MarkdownHelper::toHtmlWithDarkMode($resume->summary) !!}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- 統計資訊 -->
                    <div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-700/50">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                            <div class="text-center">
                                <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 rounded-full bg-blue-100 dark:bg-blue-900/50">
                                    <i class="fas fa-graduation-cap text-blue-600 dark:text-blue-400 text-sm sm:text-base"></i>
                                </div>
                                <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ count($resume->education ?? []) }}</div>
                                <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">學歷背景</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 rounded-full bg-green-100 dark:bg-green-900/50">
                                    <i class="fas fa-briefcase text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                                </div>
                                <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ count($resume->experience ?? []) }}</div>
                                <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">工作經驗</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 rounded-full bg-orange-100 dark:bg-orange-900/50">
                                    <i class="fas fa-eye text-orange-600 dark:text-orange-400 text-sm sm:text-base"></i>
                                </div>
                                <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $resume->views ?? 0 }}</div>
                                <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">瀏覽次數</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 rounded-full bg-purple-100 dark:bg-purple-900/50">
                                    <i class="fas fa-calendar text-purple-600 dark:text-purple-400 text-sm sm:text-base"></i>
                                </div>
                                <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ abs(round(now()->diffInDays($resume->created_at))) }}</div>
                                <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">天前建立</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 證照和認證 -->
                @if (!empty($resume->certifications))
                <div class="print-card print-section bg-white dark:bg-gray-800 {{ $templateClasses['card'] }} shadow-xl overflow-hidden {{ $templateClasses['spacing'] }} border border-gray-100 dark:border-gray-700">
                    <div class="p-4 sm:p-6">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-center sm:justify-start">
                            <i class="fas fa-certificate mr-2 sm:mr-3 text-amber-600 dark:text-amber-400 text-sm sm:text-base"></i>
                            證照和認證
                        </h2>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            @foreach ($resume->certifications as $certification)
                                <div class="rounded-xl border border-amber-100 bg-amber-50 px-4 py-4 dark:border-amber-800 dark:bg-amber-900/30">
                                    <div class="font-semibold text-amber-950 dark:text-amber-100">{{ $certification['name'] ?? '' }}</div>
                                    @if (!empty($certification['issuer']))
                                        <div class="mt-1 text-sm text-amber-800 dark:text-amber-200">{{ $certification['issuer'] }}</div>
                                    @endif
                                    <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-amber-700 dark:text-amber-300">
                                        @if (!empty($certification['issued_at']))
                                            <span class="inline-flex items-center">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $certification['issued_at'] }}
                                            </span>
                                        @endif
                                        @if (!empty($certification['url']))
                                            <a href="{{ $certification['url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center font-medium hover:text-amber-900 dark:hover:text-amber-100">
                                                <i class="fas fa-arrow-up-right-from-square mr-1"></i>
                                                驗證連結
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- 專案經驗 -->
                @if ($resumeProjects->isNotEmpty())
                <div class="print-card print-section bg-white dark:bg-gray-800 {{ $templateClasses['card'] }} shadow-xl overflow-hidden {{ $templateClasses['spacing'] }} border border-gray-100 dark:border-gray-700">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4">
                            <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white flex items-center justify-center sm:justify-start">
                                <i class="fas fa-diagram-project mr-2 sm:mr-3 text-purple-600 dark:text-purple-400 text-sm sm:text-base"></i>
                                專案經驗
                            </h2>
                            <a href="{{ route('portfolio.public', $resume->user->slug) }}" class="inline-flex items-center justify-center text-sm font-medium text-purple-700 hover:text-purple-900 dark:text-purple-300 dark:hover:text-purple-100">
                                查看完整作品集
                                <i class="fas fa-arrow-right ml-2 text-xs"></i>
                            </a>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            @foreach ($resumeProjects as $project)
                                <a href="{{ route('portfolio.project.detail', ['slug' => $resume->user->slug, 'project' => $project->id]) }}" class="group rounded-xl border border-purple-100 bg-purple-50 px-4 py-4 transition-all duration-200 hover:border-purple-200 hover:bg-purple-100 dark:border-purple-800 dark:bg-purple-900/30 dark:hover:bg-purple-900/50">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <div class="font-semibold text-purple-950 dark:text-purple-100 group-hover:text-purple-700 dark:group-hover:text-purple-200">
                                                {{ $project->title }}
                                            </div>
                                            @if ($project->completion_date)
                                                <div class="mt-1 text-xs text-purple-700 dark:text-purple-300">
                                                    {{ $project->getFormattedCompletionDate('Y年m月') }} 完成
                                                </div>
                                            @endif
                                        </div>
                                        @if ($project->is_featured)
                                            <span class="shrink-0 rounded-full bg-white px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-purple-200 dark:bg-purple-950 dark:text-purple-200 dark:ring-purple-700">精選</span>
                                        @endif
                                    </div>
                                    @if ($project->technologies)
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            @foreach (array_slice($project->technologies, 0, 3) as $tech)
                                                <span class="rounded-full bg-white px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-purple-200 dark:bg-purple-950 dark:text-purple-200 dark:ring-purple-700">
                                                    {{ $tech }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if ($project->description)
                                        <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-purple-800 dark:text-purple-200">
                                            {{ $project->description }}
                                        </p>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- 技能標籤 -->
                @if (!empty($resume->skills))
                <div class="print-card print-section bg-white dark:bg-gray-800 {{ $templateClasses['card'] }} shadow-xl overflow-hidden {{ $templateClasses['spacing'] }} border border-gray-100 dark:border-gray-700">
                    <div class="p-4 sm:p-6">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-center sm:justify-start">
                            <i class="fas fa-tags mr-2 sm:mr-3 text-cyan-600 dark:text-cyan-400 text-sm sm:text-base"></i>
                            技能標籤
                        </h2>
                        <div class="flex flex-wrap gap-2 sm:gap-3">
                            @foreach ($resume->skills as $skill)
                                <span class="inline-flex items-center rounded-full bg-cyan-50 px-3 py-1.5 text-sm font-medium text-cyan-700 ring-1 ring-cyan-200 dark:bg-cyan-900/30 dark:text-cyan-200 dark:ring-cyan-800">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- 語言能力 -->
                @if (!empty($resume->languages))
                <div class="print-card print-section bg-white dark:bg-gray-800 {{ $templateClasses['card'] }} shadow-xl overflow-hidden {{ $templateClasses['spacing'] }} border border-gray-100 dark:border-gray-700">
                    <div class="p-4 sm:p-6">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-center sm:justify-start">
                            <i class="fas fa-language mr-2 sm:mr-3 text-indigo-600 dark:text-indigo-400 text-sm sm:text-base"></i>
                            語言能力
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach ($resume->languages as $language)
                                <div class="rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3 dark:border-indigo-800 dark:bg-indigo-900/30">
                                    <div class="font-semibold text-indigo-900 dark:text-indigo-100">{{ $language['name'] ?? '' }}</div>
                                    <div class="text-sm text-indigo-700 dark:text-indigo-300">{{ $language['level'] ?? '基礎' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- 學歷背景 -->
                @if (!empty($resume->education))
                <div class="print-card print-section bg-white dark:bg-gray-800 {{ $templateClasses['card'] }} shadow-xl overflow-hidden {{ $templateClasses['spacing'] }} border border-gray-100 dark:border-gray-700">
                    <div class="bg-gradient-to-r {{ $templateClasses['sectionEducation'] }} px-4 sm:px-6 py-3 sm:py-4">
                        <h2 class="text-lg sm:text-xl font-bold text-white flex items-center justify-center sm:justify-start">
                            <i class="fas fa-graduation-cap mr-2 sm:mr-3 text-sm sm:text-base"></i>
                            學歷背景
                        </h2>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="space-y-4 sm:space-y-6">
                            @foreach ($resume->education as $index => $edu)
                            <div class="relative pb-4 sm:pb-6 last:pb-0">
                                <!-- 時間軸線 -->
                                @if($index < count($resume->education) - 1)
                                <div class="absolute left-5 sm:left-6 top-10 sm:top-12 w-0.5 h-full bg-gradient-to-b from-blue-200 to-blue-100 dark:from-blue-800 dark:to-blue-900"></div>
                                @endif
                                
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <!-- 時間軸點 -->
                                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-lg">
                                        <i class="fas fa-university text-white text-xs sm:text-sm"></i>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3 sm:p-4 border border-gray-100 dark:border-gray-600">
                                            <div class="flex flex-col space-y-2 sm:space-y-0 sm:flex-row sm:items-start sm:justify-between mb-2 sm:mb-3">
                                                <div>
                                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-1">
                                                        {{ $edu['school'] }}
                                                    </h3>
                                                    <div class="text-blue-600 dark:text-blue-400 font-medium text-xs sm:text-sm">
                                                        {{ $edu['degree'] }} · {{ $edu['field'] }}
                                                    </div>
                                                </div>
                                                <div class="mt-1 sm:mt-0">
                                                    <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                                        <i class="fas fa-calendar mr-1 text-xs"></i>
                                                        {{ $edu['start_date'] }} - {{ $edu['end_date'] }}
                                                    </span>
                                                </div>
                                            </div>
                                            @if (!empty($edu['description']))
                                            <p class="text-gray-600 dark:text-gray-300 text-xs sm:text-sm leading-relaxed">
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
                <div class="print-card print-section bg-white dark:bg-gray-800 {{ $templateClasses['card'] }} shadow-xl overflow-hidden {{ $templateClasses['spacing'] }} border border-gray-100 dark:border-gray-700">
                    <div class="bg-gradient-to-r {{ $templateClasses['sectionExperience'] }} px-4 sm:px-6 py-3 sm:py-4">
                        <h2 class="text-lg sm:text-xl font-bold text-white flex items-center justify-center sm:justify-start">
                            <i class="fas fa-briefcase mr-2 sm:mr-3 text-sm sm:text-base"></i>
                            工作經驗
                        </h2>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="space-y-4 sm:space-y-6">
                            @foreach ($resume->experience as $index => $exp)
                            <div class="relative pb-4 sm:pb-6 last:pb-0">
                                <!-- 時間軸線 -->
                                @if($index < count($resume->experience) - 1)
                                <div class="absolute left-5 sm:left-6 top-10 sm:top-12 w-0.5 h-full bg-gradient-to-b from-green-200 to-green-100 dark:from-green-800 dark:to-green-900"></div>
                                @endif
                                
                                <div class="flex items-start space-x-3 sm:space-x-4">
                                    <!-- 時間軸點 -->
                                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center shadow-lg">
                                        @if($exp['current'] ?? false)
                                            <i class="fas fa-play text-white text-xs sm:text-sm"></i>
                                        @else
                                            <i class="fas fa-building text-white text-xs sm:text-sm"></i>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3 sm:p-4 border border-gray-100 dark:border-gray-600">
                                            <div class="flex flex-col space-y-2 sm:space-y-0 sm:flex-row sm:items-start sm:justify-between mb-2 sm:mb-3">
                                                <div>
                                                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white mb-1">
                                                        {{ $exp['position'] }}
                                                    </h3>
                                                    <div class="text-green-600 dark:text-green-400 font-medium text-xs sm:text-sm">
                                                        {{ $exp['company'] }}
                                                    </div>
                                                </div>
                                                <div class="mt-1 sm:mt-0 flex flex-col items-start sm:items-end space-y-1">
                                                    <span class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                                        <i class="fas fa-calendar mr-1 text-xs"></i>
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
                                            <p class="text-gray-600 dark:text-gray-300 text-xs sm:text-sm leading-relaxed whitespace-pre-line">
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
        <footer class="print-hide bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-8 mt-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center space-x-3 mb-4 md:mb-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-purple-600">
                            <i class="fas fa-file-alt text-white text-sm"></i>
                        </div>
                        <span class="font-bold text-gray-900 dark:text-white">L13CV</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">履歷平台</span>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        © {{ date('Y') }} L13CV. 讓您的履歷更專業
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Flux Scripts -->
    @fluxScripts
    @livewireScripts
</body>

</html>

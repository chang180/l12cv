<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <?php
    use function Livewire\Volt\{state};
    state(['resume']);
    ?>

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="py-8">
            {{-- 回首頁按鈕 --}}
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24"
                        height="24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    返回首頁
                </a>
            </div>

            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                @if ($resume)
                    {{-- 基本資料卡片 --}}
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden mb-6">
                        <div class="p-6">
                            <div class="flex items-start space-x-6">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-24 h-24 rounded-full bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center">
                                        <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">
                                            {{ substr($resume->user->name ?? '未知', 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ $resume->user->name ?? '未知' }}
                                    </h1>
                                    <h2 class="text-xl text-primary-600 dark:text-primary-400 mb-4">
                                        {{ $resume->title }}
                                    </h2>
                                    @if ($resume->summary)
                                        <p class="text-gray-600 dark:text-gray-300 whitespace-pre-line">
                                            {{ $resume->summary }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {{-- 學歷背景 --}}
                        @if (!empty($resume->education))
                            <div class="lg:col-span-2">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">學歷背景</h2>
                                    </div>
                                    <div class="p-6">
                                        <div class="space-y-6">
                                            @foreach ($resume->education as $edu)
                                                <div class="relative pb-6 last:pb-0">
                                                    <div class="flex flex-col sm:flex-row sm:items-start">
                                                        <div class="mb-2 sm:mb-0 sm:w-40 sm:flex-shrink-0">
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $edu['start_date'] }} - {{ $edu['end_date'] }}
                                                            </div>
                                                        </div>
                                                        <div class="flex-1">
                                                            <h3
                                                                class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                                                {{ $edu['school'] }}
                                                            </h3>
                                                            <div
                                                                class="text-primary-600 dark:text-primary-400 text-sm mb-2">
                                                                {{ $edu['degree'] }} · {{ $edu['field'] }}
                                                            </div>
                                                            @if (!empty($edu['description']))
                                                                <p class="text-gray-600 dark:text-gray-300 text-sm">
                                                                    {{ $edu['description'] }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 工作經驗 --}}
                        @if (!empty($resume->experience))
                            <div class="lg:col-span-3">
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">工作經驗</h2>
                                    </div>
                                    <div class="p-6">
                                        <div class="space-y-8">
                                            @foreach ($resume->experience as $exp)
                                                <div class="relative pb-8 last:pb-0">
                                                    <div class="flex flex-col sm:flex-row sm:items-start">
                                                        <div class="mb-2 sm:mb-0 sm:w-40 sm:flex-shrink-0">
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $exp['start_date'] }} -
                                                                {{ $exp['current'] ? '至今' : $exp['end_date'] }}
                                                            </div>
                                                        </div>
                                                        <div class="flex-1">
                                                            <h3
                                                                class="text-base font-semibold text-gray-900 dark:text-white mb-1">
                                                                {{ $exp['position'] }}
                                                            </h3>
                                                            <div
                                                                class="text-primary-600 dark:text-primary-400 text-sm mb-2">
                                                                {{ $exp['company'] }}
                                                            </div>
                                                            @if (!empty($exp['description']))
                                                                <p
                                                                    class="text-gray-600 dark:text-gray-300 text-sm whitespace-pre-line">
                                                                    {{ $exp['description'] }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8">
                        <div class="text-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">找不到此履歷</h3>
                            <p class="text-gray-500 dark:text-gray-400">此履歷可能不存在或未公開</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>

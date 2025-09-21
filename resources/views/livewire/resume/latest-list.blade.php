<?php

use function Livewire\Volt\{state, mount};
use App\Models\Resume;
use Illuminate\Support\Facades\Storage;

state(['resumes' => []]);

mount(function () {
    $this->resumes = Resume::where('is_public', true)
        ->latest()
        ->take(5)
        ->with('user') // 預加載用戶關係以避免 N+1 查詢問題
        ->get();
});

?>

<div class="mt-6 sm:mt-8 space-y-4 sm:space-y-6">
    @forelse($resumes as $resume)
        <a href="{{ route('resume.public', $resume->slug) }}" 
           class="group block p-4 sm:p-6 bg-gradient-to-r from-white/80 to-slate-50/80 dark:from-slate-800/80 dark:to-slate-700/80 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl border border-slate-200/50 dark:border-slate-600/50 transform hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start space-x-3 sm:space-x-4">
                <!-- 頭像區域 -->
                <div class="flex-shrink-0">
                    @if ($resume->user && $resume->user->avatar)
                        <img src="{{ Storage::url($resume->user->avatar) }}"
                             alt="{{ $resume->user->name ?? '未知' }}"
                             class="w-12 h-12 sm:w-16 sm:h-16 rounded-full object-cover ring-2 sm:ring-4 ring-white/50 dark:ring-slate-600/50 group-hover:ring-blue-200/50 dark:group-hover:ring-blue-400/50 transition-all duration-300">
                    @else
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center ring-2 sm:ring-4 ring-white/50 dark:ring-slate-600/50 group-hover:ring-blue-200/50 dark:group-hover:ring-blue-400/50 transition-all duration-300">
                            <span class="text-lg sm:text-xl font-bold text-white">
                                {{ substr($resume->user->name ?? '未知', 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- 履歷內容區域 -->
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2">
                        <div class="min-w-0 flex-1 mb-2 sm:mb-0">
                            <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-slate-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300 truncate">
                                {{ $resume->title }}
                            </h3>
                            <p class="text-xs sm:text-sm font-medium text-slate-600 dark:text-slate-300 mt-1">
                                {{ $resume->user->name ?? '未知' }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                                <i class="fas fa-eye mr-1"></i>
                                {{ $resume->views ?? 0 }}
                            </span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 hidden sm:inline">
                                {{ $resume->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <!-- 手機版時間顯示 -->
                    <div class="sm:hidden mb-2">
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $resume->updated_at->diffForHumans() }}
                        </span>
                    </div>

                    @if($resume->summary)
                        <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 line-clamp-2 mb-3">
                            {!! nl2br(e($resume->summary)) !!}
                        </div>
                    @endif

                    <!-- 工作經驗和統計信息 -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs sm:text-sm">
                            @php
                                $experiences = $resume->experience ?? [];
                                $educations = $resume->education ?? [];
                                $latestExperience = is_array($experiences) ? end($experiences) : null;
                            @endphp

                            @if($latestExperience)
                                <div class="flex items-center text-slate-600 dark:text-slate-400">
                                    <i class="fas fa-briefcase mr-1 sm:mr-2 text-blue-500 text-xs sm:text-sm"></i>
                                    <span class="truncate max-w-24 sm:max-w-32">
                                        {{ $latestExperience['company'] ?? '' }}
                                        @if(isset($latestExperience['company']) && isset($latestExperience['position']))
                                            ·
                                        @endif
                                        {{ $latestExperience['position'] ?? '' }}
                                    </span>
                                </div>
                            @endif

                            @if(count($educations) > 0)
                                <div class="flex items-center text-slate-600 dark:text-slate-400">
                                    <i class="fas fa-graduation-cap mr-1 sm:mr-2 text-green-500 text-xs sm:text-sm"></i>
                                    <span>{{ count($educations) }} 項學歷</span>
                                </div>
                            @endif

                            @if(count($experiences) > 0)
                                <div class="flex items-center text-slate-600 dark:text-slate-400">
                                    <i class="fas fa-briefcase mr-1 sm:mr-2 text-purple-500 text-xs sm:text-sm"></i>
                                    <span>{{ count($experiences) }} 項經驗</span>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center text-blue-600 dark:text-blue-400 text-xs sm:text-sm font-medium mt-2 sm:mt-0">
                            <span>查看履歷</span>
                            <i class="fas fa-arrow-right ml-1 sm:ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="text-center py-12">
            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-600 rounded-full flex items-center justify-center">
                <i class="fas fa-file-alt text-2xl text-slate-400 dark:text-slate-500"></i>
            </div>
            <p class="text-lg font-medium text-slate-600 dark:text-slate-400 mb-2">目前還沒有公開的履歷</p>
            @guest
                <p class="text-sm text-slate-500 dark:text-slate-500">
                    <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors duration-300">
                        註冊帳號
                    </a>
                    來建立第一份履歷！
                </p>
            @endguest
        </div>
    @endforelse
</div>

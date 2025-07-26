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

<div class="mt-8 space-y-4">
    @forelse($resumes as $resume)
        <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
            <a href="{{ route('resume.public', $resume->slug) }}" class="block">
                <div class="flex items-start">
                    <!-- 頭像區域 - 調整為更小的尺寸 -->
                    <div class="flex-shrink-0 mr-3">
                        @if ($resume->user && $resume->user->avatar)
                            <img src="{{ Storage::url($resume->user->avatar) }}"
                                 alt="{{ $resume->user->name ?? '未知' }}"
                                 class="w-20 h-20 rounded-full object-cover">
                        @else
                            <div class="w-20 h-20 p-2 rounded-full bg-primary-50 dark:bg-primary-900/50 flex items-center justify-center">

                                <span class="text-lg font-bold text-primary-600 dark:text-primary-400"> <!-- 從 text-lg 縮小到 text-sm -->
                                    {{ substr($resume->user->name ?? '未知', 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- 履歷內容區域 -->
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ $resume->title }}
                                </h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $resume->user->name ?? '未知' }}
                                </p>
                                @if($resume->summary)
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                        {{ $resume->summary }}
                                    </p>
                                @endif
                            </div>
                            <span class="text-xs text-gray-500">
                                {{ $resume->updated_at->diffForHumans() }}
                            </span>
                        </div>

                        <div class="mt-2">
                            @php
                                $experiences = $resume->experience ?? [];
                                $latestExperience = is_array($experiences) ? end($experiences) : null;
                            @endphp

                            @if($latestExperience)
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $latestExperience['company'] ?? '' }}
                                    @if(isset($latestExperience['company']) && isset($latestExperience['position']))
                                        ·
                                    @endif
                                    {{ $latestExperience['position'] ?? '' }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="text-center py-8">
            <p class="text-gray-500 dark:text-gray-400">目前還沒有公開的履歷</p>
            @guest
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-500">
                    <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-500">
                        註冊帳號
                    </a>
                    來建立第一份履歷！
                </p>
            @endguest
        </div>
    @endforelse
</div>

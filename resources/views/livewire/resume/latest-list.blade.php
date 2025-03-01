<?php

use function Livewire\Volt\{state, mount};
use App\Models\Resume;

state(['resumes' => []]);

mount(function () {
    $this->resumes = Resume::where('is_public', true)
        ->latest()
        ->take(5)
        ->get();
});

?>

<div class="mt-8 space-y-4">
    @forelse($resumes as $resume)
        <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
            <a href="{{ route('resume.public', $resume->slug) }}" class="block">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ $resume->title }}
                        </h3>
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

<?php

use function Livewire\Volt\{state, mount};
use App\Models\Resume;

state(['resume' => null]);

mount(function ($slug) {
    $this->resume = Resume::where('slug', $slug)
        ->where('is_public', true)
        ->firstOrFail();
});

?>

<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($resume)
                        <div class="space-y-6">
                            <div>
                                <h1 class="text-2xl font-bold">{{ $resume->title }}</h1>
                                @if($resume->summary)
                                    <p class="mt-4 text-gray-600 dark:text-gray-300">{{ $resume->summary }}</p>
                                @endif
                            </div>

                            @if(!empty($resume->education))
                                <div class="mt-8">
                                    <h2 class="text-xl font-semibold mb-4">學歷</h2>
                                    <div class="space-y-4">
                                        @foreach($resume->education as $edu)
                                            <div class="border-l-4 border-primary-500 pl-4">
                                                <h3 class="font-medium">{{ $edu['school'] }}</h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $edu['degree'] }} · {{ $edu['field'] }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $edu['start_date'] }} - {{ $edu['end_date'] }}
                                                </p>
                                                @if(!empty($edu['description']))
                                                    <p class="mt-2 text-gray-600 dark:text-gray-300">
                                                        {{ $edu['description'] }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(!empty($resume->experience))
                                <div class="mt-8">
                                    <h2 class="text-xl font-semibold mb-4">工作經驗</h2>
                                    <div class="space-y-4">
                                        @foreach($resume->experience as $exp)
                                            <div class="border-l-4 border-primary-500 pl-4">
                                                <h3 class="font-medium">{{ $exp['position'] }}</h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $exp['company'] }}
                                                </p>
                                                <p class="text-sm text-gray-500">
                                                    {{ $exp['start_date'] }} -
                                                    {{ $exp['current'] ? '至今' : $exp['end_date'] }}
                                                </p>
                                                @if(!empty($exp['description']))
                                                    <p class="mt-2 text-gray-600 dark:text-gray-300">
                                                        {{ $exp['description'] }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500">找不到此履歷或履歷未公開</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

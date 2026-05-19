<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

it('declares the correct GitHub repo in CLAUDE.md project identity', function () {
    $contents = File::get(base_path('CLAUDE.md'));

    expect($contents)
        ->toContain('chang180/l12cv')
        ->toContain('非 chang180/chang180')
        ->toContain('#10-proj-l12cv')
        ->toContain('C0B47UBS2HH');
});

it('keeps orchestrator docs bound to chang180/l12cv and the product Slack channel', function () {
    $bindings = [
        'docs/progress.md' => ['chang180/l12cv', '#10-proj-l12cv', 'C0B47UBS2HH'],
        '.cursor/rules/orchestrator.mdc' => ['chang180/l12cv', '#10-proj-l12cv', 'C0B47UBS2HH'],
        'docs/agent-handoff/README.md' => ['chang180/l12cv', '#10-proj-l12cv'],
    ];

    foreach ($bindings as $file => $needles) {
        $contents = File::get(base_path($file));

        foreach ($needles as $needle) {
            expect($contents)->toContain($needle);
        }
    }
});

it('does not bind docs to the wrong chang180/chang180 repository', function () {
    $docs = collect(File::allFiles(base_path('docs')))
        ->map(fn (SplFileInfo $file) => $file->getPathname())
        ->filter(fn (string $path) => str_ends_with($path, '.md'));

    foreach ($docs as $path) {
        $contents = File::get($path);

        expect($contents)->not->toContain('chang180/chang180');
    }
});

<?php

use App\Livewire\Resume\Portfolio\ProjectForm;
use App\Livewire\Resume\Portfolio\ProjectList;
use App\Models\Project;
use App\Models\User;
use Livewire\Livewire;

test('用戶可以建立作品標籤並自動清理空白與重複值', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(ProjectForm::class, ['resumeId' => $user->resume->id])
        ->set('title', '標籤測試作品')
        ->set('tags', ' SaaS, 後台系統, SaaS, , 開源專案 ')
        ->call('saveProject')
        ->assertHasNoErrors();

    expect($user->projects()->where('title', '標籤測試作品')->first()->tags)->toBe([
        'SaaS',
        '後台系統',
        '開源專案',
    ]);
});

test('用戶可以拖曳重新排序作品集項目', function () {
    $user = User::factory()->create();

    $first = Project::create([
        'user_id' => $user->id,
        'title' => '第一個作品',
        'order' => 1,
    ]);

    $second = Project::create([
        'user_id' => $user->id,
        'title' => '第二個作品',
        'order' => 2,
    ]);

    $third = Project::create([
        'user_id' => $user->id,
        'title' => '第三個作品',
        'order' => 3,
    ]);

    Livewire::actingAs($user)
        ->test(ProjectList::class, ['resumeId' => $user->resume->id])
        ->call('reorderProjects', [$third->id, $first->id, $second->id])
        ->assertDispatched('projectOrderSaved');

    expect(Project::query()->orderBy('order')->pluck('title')->all())->toBe([
        '第三個作品',
        '第一個作品',
        '第二個作品',
    ]);
});

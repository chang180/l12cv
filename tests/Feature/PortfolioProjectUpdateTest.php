<?php

use App\Livewire\Resume\Portfolio\ProjectForm;
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

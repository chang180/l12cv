<?php

use App\Livewire\Resume\Edit;
use App\Models\User;
use Livewire\Livewire;

test('用戶可以更新履歷基本資料', function () {
    // 創建用戶（UserObserver 會自動創建履歷）
    $user = User::factory()->create();

    // 取得已存在的履歷並更新其資料
    $resume = $user->resume;
    $resume->update([
        'title' => '原始標題',
        'slug' => 'original-slug',
        'summary' => '原始簡介',
    ]);

    // 測試更新功能
    Livewire::actingAs($user)
        ->test(Edit::class)
        ->set('title', '新標題')
        ->set('summary', '新簡介')
        ->call('updateBasicInfo')
        ->assertHasNoErrors();

    // 驗證資料已更新
    $resume->refresh();
    expect($resume->title)->toBe('新標題');
    expect($resume->summary)->toBe('新簡介');
});

test('用戶可以更新履歷模板', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class)
        ->set('template', 'modern')
        ->call('updateBasicInfo')
        ->assertHasNoErrors();

    expect($user->resume->fresh()->template)->toBe('modern');
});

test('無效履歷模板會被拒絕', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class)
        ->set('template', 'unknown-template')
        ->call('updateBasicInfo')
        ->assertHasErrors(['template']);
});

test('用戶可以更新技能標籤並自動清理空白與重複值', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class)
        ->set('skills', [' Laravel ', 'Livewire', '', 'Laravel'])
        ->call('updateSkills')
        ->assertHasNoErrors();

    expect($user->resume->fresh()->skills)->toBe(['Laravel', 'Livewire']);
});

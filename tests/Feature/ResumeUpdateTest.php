<?php

use App\Models\User;
use App\Models\Resume;
use Livewire\Volt\Volt;

test('用戶可以更新履歷基本資料', function () {
    // 創建用戶
    $user = User::factory()->create();
    
    // 直接創建履歷，不使用 factory
    $resume = Resume::create([
        'user_id' => $user->id,
        'title' => '原始標題',
        'slug' => 'original-slug',
        'summary' => '原始簡介',
        'education' => [],
        'experience' => [],
        'is_public' => false,
    ]);

    // 測試更新功能
    Volt::test('resume.edit')
        ->actingAs($user)
        ->set('title', '新標題')
        ->set('summary', '新簡介')
        ->call('updateBasicInfo')
        ->assertHasNoErrors();

    // 驗證資料已更新
    $resume->refresh();
    expect($resume->title)->toBe('新標題');
    expect($resume->summary)->toBe('新簡介');
});
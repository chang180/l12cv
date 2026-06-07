<?php

use App\Livewire\Resume\Edit;
use App\Models\User;
use Livewire\Livewire;
use Livewire\Volt\Volt;

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
    expect($resume->versions()->where('event', 'basic.updated')->count())->toBe(1);
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

test('基本資料可以自動儲存', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class)
        ->set('title', '自動儲存標題')
        ->set('summary', '自動儲存簡介')
        ->set('template', 'compact')
        ->call('autoSaveBasicInfo')
        ->assertHasNoErrors()
        ->assertDispatched('auto-saved');

    $resume = $user->resume->fresh();

    expect($resume->title)->toBe('自動儲存標題');
    expect($resume->summary)->toBe('自動儲存簡介');
    expect($resume->template)->toBe('compact');
    expect($resume->versions()->where('event', 'basic.autosaved')->count())->toBe(1);
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

test('用戶可以更新語言能力並清理空白項目', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class)
        ->set('languages', [
            ['name' => ' 英文 ', 'level' => '流利'],
            ['name' => '', 'level' => '母語'],
            ['name' => '日文', 'level' => ''],
        ])
        ->call('updateLanguages')
        ->assertHasNoErrors();

    expect($user->resume->fresh()->languages)->toBe([
        ['name' => '英文', 'level' => '流利'],
        ['name' => '日文', 'level' => '基礎'],
    ]);
});

test('用戶可以更新證照和認證並清理空白項目', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Edit::class)
        ->set('certifications', [
            [
                'name' => ' AWS Certified Developer ',
                'issuer' => ' Amazon Web Services ',
                'issued_at' => '2026-05-14',
                'url' => ' https://example.com/cert ',
            ],
            [
                'name' => '',
                'issuer' => 'Ignored Issuer',
                'issued_at' => '2026-05-14',
                'url' => '',
            ],
        ])
        ->call('updateCertifications')
        ->assertHasNoErrors();

    expect($user->resume->fresh()->certifications)->toBe([
        [
            'name' => 'AWS Certified Developer',
            'issuer' => 'Amazon Web Services',
            'issued_at' => '2026-05-14',
            'url' => 'https://example.com/cert',
        ],
    ]);
});

test('用戶儲存學歷後會看到成功提示', function () {
    $user = User::factory()->create();

    $user->resume->update([
        'education' => [
            [
                'school' => '舊學校',
                'degree' => '學士',
                'field' => '資訊工程',
                'start_date' => '2018-09-01',
                'end_date' => '2022-06-30',
                'description' => '',
            ],
        ],
    ]);

    Volt::actingAs($user)
        ->test('resume.edit')
        ->set('education.0.school', '新學校')
        ->call('updateEducation')
        ->assertHasNoErrors()
        ->assertSet('statusMessage', '✅ 學歷資料已更新')
        ->assertSee('✅ 學歷資料已更新');

    expect($user->resume->fresh()->education[0]['school'])->toBe('新學校');
});

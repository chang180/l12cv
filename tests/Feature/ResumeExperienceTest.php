<?php

use App\Livewire\Resume\MarkdownEditor;
use App\Models\User;
use App\Support\ResumeExperience;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;

uses(RefreshDatabase::class);

test('ResumeExperience sort puts current job first and orders by end date descending', function () {
    $experience = [
        [
            'company' => '舊公司',
            'position' => 'Junior',
            'start_date' => '2018-01-01',
            'end_date' => '2020-12-31',
            'current' => false,
            'description' => '',
        ],
        [
            'company' => '現職公司',
            'position' => 'Senior',
            'start_date' => '2023-01-01',
            'end_date' => '',
            'current' => true,
            'description' => '',
        ],
        [
            'company' => '中間公司',
            'position' => 'Mid',
            'start_date' => '2021-01-01',
            'end_date' => '2022-12-31',
            'current' => false,
            'description' => '',
        ],
    ];

    $sorted = ResumeExperience::sort($experience);

    expect($sorted[0]['company'])->toBe('現職公司')
        ->and($sorted[1]['company'])->toBe('中間公司')
        ->and($sorted[2]['company'])->toBe('舊公司');
});

test('public resume hides download buttons by default but keeps print', function () {
    $user = User::factory()->create([
        'name' => 'Sort Test User',
        'slug' => 'sort-test-user',
    ]);

    $user->resume->update([
        'title' => '測試履歷',
        'slug' => 'sort-test-user',
        'is_public' => true,
        'experience' => [
            [
                'company' => '較舊公司',
                'position' => 'Engineer',
                'start_date' => '2019-01-01',
                'end_date' => '2021-12-31',
                'current' => false,
                'description' => '舊工作',
            ],
            [
                'company' => '最新公司',
                'position' => 'Lead',
                'start_date' => '2024-01-01',
                'end_date' => '',
                'current' => true,
                'description' => '**最新**工作',
            ],
        ],
    ]);

    $response = $this->get('/@sort-test-user');

    $response->assertOk()
        ->assertSee('列印')
        ->assertSee('最新公司')
        ->assertSeeInOrder(['最新公司', '較舊公司'])
        ->assertDontSee('下載 PDF')
        ->assertDontSee('下載 DOCX')
        ->assertDontSee('下載全部')
        ->assertSee('print-item', false)
        ->assertSee('print-section-header', false);
});

test('markdown editor component renders for summary field', function () {
    Livewire\Livewire::test(MarkdownEditor::class, [
        'content' => '測試內容',
        'parentEvent' => 'update-parent-summary',
    ])
        ->assertSee('data-markdown-editor-root', false)
        ->assertSee('markdown-editor-', false);
});

test('adding work experience renders auto-growing description textarea', function () {
    $user = User::factory()->create();

    Volt::actingAs($user)
        ->test('resume.edit')
        ->set('currentTab', 'experience')
        ->call('addExperience')
        ->assertHasNoErrors()
        ->assertSee('工作描述')
        ->assertSee('描述您的工作內容、主要職責與成果', false)
        ->assertDontSee('data-markdown-editor-root', false);
});

test('adding second work experience renders description textarea for each entry', function () {
    $user = User::factory()->create();

    $user->resume->update([
        'experience' => [
            [
                'company' => '第一家公司',
                'position' => 'Engineer',
                'start_date' => '2020-01-01',
                'end_date' => '2022-12-31',
                'current' => false,
                'description' => '第一份工作',
            ],
        ],
    ]);

    Volt::actingAs($user)
        ->test('resume.edit')
        ->set('currentTab', 'experience')
        ->call('addExperience')
        ->assertHasNoErrors()
        ->assertSee('工作經驗 #1')
        ->assertSee('工作經驗 #2')
        ->assertSee('wire:model="experience.0.description"', false)
        ->assertSee('wire:model="experience.1.description"', false);
});

test('user can save work experience from resume edit page', function () {
    $user = User::factory()->create();

    $user->resume->update([
        'experience' => [
            [
                'company' => '舊公司',
                'position' => 'Engineer',
                'start_date' => '2020-01-01',
                'end_date' => '2022-12-31',
                'current' => false,
                'description' => '原始描述',
            ],
        ],
    ]);

    Volt::actingAs($user)
        ->test('resume.edit')
        ->set('experience.0.company', '新公司')
        ->set('experience.0.description', '更新後的描述')
        ->call('updateExperience')
        ->assertHasNoErrors()
        ->assertDispatched('notify');

    $experience = $user->resume->fresh()->experience;

    expect($experience[0]['company'])->toBe('新公司')
        ->and($experience[0]['description'])->toBe('更新後的描述');
});

test('public resume shows download buttons when enabled in config', function () {
    config(['resume.downloads_enabled' => true]);

    $user = User::factory()->create([
        'slug' => 'download-test-user',
    ]);

    $user->resume->update([
        'slug' => 'download-test-user',
        'is_public' => true,
    ]);

    $this->get('/@download-test-user')
        ->assertOk()
        ->assertSee('下載 PDF')
        ->assertSee('下載 DOCX')
        ->assertSee('下載全部');
});

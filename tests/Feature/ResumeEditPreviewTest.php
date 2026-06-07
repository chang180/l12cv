<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;

uses(RefreshDatabase::class);

test('edit page preview renders stored summary markdown as html', function () {
    $user = User::factory()->create();

    $user->resume->update([
        'summary' => "# 測試標題\n\n**粗體文字**",
    ]);

    Volt::actingAs($user)
        ->test('resume.edit')
        ->assertSee('測試標題')
        ->assertSee('粗體文字')
        ->assertSee('text-2xl font-bold', false)
        ->assertDontSee('# 測試標題');
});

test('edit page preview updates when summary state changes', function () {
    $user = User::factory()->create();

    Volt::actingAs($user)
        ->test('resume.edit')
        ->set('summary', "## 新標題\n\n*斜體*")
        ->assertSee('新標題')
        ->assertSee('斜體')
        ->assertSee('text-xl font-bold', false);
});

test('edit page receives summary updates from markdown editor event', function () {
    $user = User::factory()->create();

    Volt::actingAs($user)
        ->test('resume.edit')
        ->dispatch('update-parent-summary', content: "# 事件標題\n\n段落文字")
        ->assertSet('summary', "# 事件標題\n\n段落文字")
        ->assertSee('事件標題')
        ->assertSee('段落文字')
        ->assertDontSee('# 事件標題');
});

test('public resume preview preserves single line breaks in summary', function () {
    $user = User::factory()->create([
        'slug' => 'summary-break-test',
    ]);

    $user->resume->update([
        'slug' => 'summary-break-test',
        'is_public' => true,
        'summary' => "✔ 具備 5 年以上 PHP 後端開發經驗\n✔ 參與金流系統開發\n✔ 熟悉 Git 版本控制",
    ]);

    $this->get('/@summary-break-test')
        ->assertOk()
        ->assertSee('具備 5 年以上 PHP 後端開發經驗')
        ->assertSee('參與金流系統開發')
        ->assertSee('熟悉 Git 版本控制')
        ->assertSee('<br', false);
});

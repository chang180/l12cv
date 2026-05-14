<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

uses(RefreshDatabase::class);

test('authenticated resume and settings pages render after the Laravel 13 upgrade', function () {
    Config::set('services.google.enabled', false);

    $user = User::factory()->create();
    $user->resume->update([
        'title' => 'Test User 的履歷',
        'slug' => 'test-user',
        'template' => 'modern',
        'summary' => '具備 Laravel 與 Livewire 專案經驗。',
        'is_public' => true,
    ]);

    $this->actingAs($user);

    $this->get('/resume')
        ->assertOk()
        ->assertSee('履歷管理中心');

    $this->get('/resume/edit')
        ->assertOk()
        ->assertSee('編輯履歷')
        ->assertSee('作品集');

    $this->get('/settings/profile')
        ->assertOk()
        ->assertSee('個人資料')
        ->assertSee('本機開發預設未啟用 Google');
});

test('public resume portfolio project and pdf pages render with seeded content', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
        'slug' => 'test-user',
    ]);

    $user->resume->update([
        'title' => 'Test User 的履歷',
        'slug' => 'test-user',
        'template' => 'modern',
        'summary' => '具備 Laravel 與 Livewire 專案經驗。',
        'skills' => ['Laravel', 'Livewire', 'Tailwind CSS'],
        'languages' => [
            ['name' => '英文', 'level' => '流利'],
        ],
        'certifications' => [
            [
                'name' => 'AWS Certified Developer',
                'issuer' => 'Amazon Web Services',
                'issued_at' => '2026-05-14',
                'url' => 'https://example.com/cert',
            ],
        ],
        'education' => [
            [
                'school' => '測試大學',
                'degree' => '學士',
                'field' => '資訊工程',
                'start_date' => '2018-09-01',
                'end_date' => '2022-06-30',
                'description' => '軟體工程與資料庫',
            ],
        ],
        'experience' => [
            [
                'company' => '測試公司',
                'position' => '後端工程師',
                'start_date' => '2022-07-01',
                'end_date' => '',
                'current' => true,
                'description' => '負責 Laravel 平台開發',
            ],
        ],
        'is_public' => true,
    ]);

    $project = Project::create([
        'user_id' => $user->id,
        'title' => 'L13CV 驗證作品',
        'description' => '用於升級後 walkthrough 的公開作品集項目。',
        'url' => 'https://example.com/demo',
        'github_url' => 'https://github.com/example/l13cv',
        'technologies' => ['Laravel', 'Livewire', 'Tailwind'],
        'completion_date' => '2026-05-14',
        'is_featured' => true,
        'order' => 1,
    ]);

    $this->get('/@test-user')
        ->assertOk()
        ->assertSee('data-resume-template="modern"', false)
        ->assertSee('Test User 的履歷')
        ->assertSee('專案經驗')
        ->assertSee('L13CV 驗證作品')
        ->assertSee('查看完整作品集')
        ->assertSee('列印')
        ->assertSee('Tailwind CSS')
        ->assertSee('英文')
        ->assertSee('流利')
        ->assertSee('AWS Certified Developer')
        ->assertSee('Amazon Web Services')
        ->assertSee('下載 PDF');

    $this->get('/p/test-user')
        ->assertOk()
        ->assertSee('L13CV 驗證作品')
        ->assertSee('Laravel');

    $this->get("/p/test-user/project/{$project->id}")
        ->assertOk()
        ->assertSee('用於升級後 walkthrough 的公開作品集項目。');

    $this->get('/@test-user/pdf')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/pdf');
});

test('resume templates fall back safely and pdf supports all built in templates', function () {
    $user = User::factory()->create([
        'name' => 'Template User',
        'slug' => 'template-user',
    ]);

    foreach (['classic', 'modern', 'compact'] as $template) {
        $slug = "template-user-{$template}";

        $user->resume->update([
            'title' => "{$template} template",
            'slug' => $slug,
            'template' => $template,
            'summary' => '模板回歸測試',
            'is_public' => true,
        ]);

        $this->get("/@{$slug}")
            ->assertOk()
            ->assertSee("data-resume-template=\"{$template}\"", false);

        $this->get("/@{$slug}/pdf")
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    $user->resume->update([
        'slug' => 'template-user-invalid',
        'template' => 'invalid-template',
    ]);

    $this->get('/@template-user-invalid')
        ->assertOk()
        ->assertSee('data-resume-template="classic"', false);
});

test('google oauth remains disabled on local guest auth pages', function () {
    Config::set('services.google.enabled', false);

    $this->get('/login')
        ->assertOk()
        ->assertSee('本機開發預設未啟用 Google 登入，請使用電子郵件登入。');

    $this->get('/register')
        ->assertOk()
        ->assertSee('本機開發預設未啟用 Google 註冊，請先使用電子郵件建立帳戶。');
});

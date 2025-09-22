<?php

use App\Http\Controllers\RedisTestController;
use App\Http\Controllers\ResumePdfController;
use App\Models\Project;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Redis 測試路由
Route::get('/test-redis', [RedisTestController::class, 'testRedis'])->name('test.redis');
// Redis 實際應用案例路由
Route::get('/redis-use-cases', [RedisTestController::class, 'practicalUseCases'])->name('redis.use-cases');
// Redis 信用卡交易緩存演示
Route::get('/credit-card-cache', [RedisTestController::class, 'creditCardTransactionCache'])->name('credit.card.cache');

// 公開履歷路由 - 不需要驗證
Route::get('/@{slug}', function ($slug) {
    $resume = Resume::where('slug', $slug)
        ->where('is_public', true)
        ->with('user')  // 預先載入用戶資料
        ->firstOrFail();

    // 增加履歷瀏覽數（帶防刷機制）
    $resume->incrementViewsWithTracking(
        request()->ip(),
        request()->userAgent()
    );

    return view('livewire.resume.public', [
        'resume' => $resume,
    ]);
})->name('resume.public');

// PDF 導出路由
Route::get('/@{slug}/pdf', [ResumePdfController::class, 'download'])->name('resume.pdf');

// 公開作品集路由 - 不需要驗證 (使用 slug 而不是 ID)
Route::get('/p/{slug}', function ($slug) {
    $user = User::where('slug', $slug)->firstOrFail();
    $projects = $user->projects()->orderBy('order')->orderBy('created_at', 'desc')->get();

    // 獲取用戶的公開履歷，用於頁面切換
    $resume = $user->resume()->where('is_public', true)->first();

    return view('livewire.portfolio.public', [
        'user' => $user,
        'projects' => $projects,
        'resume' => $resume,
    ]);
})->name('portfolio.public');

// 單個作品詳情頁路由 - 不需要驗證 (同樣使用 slug)
Route::get('/p/{slug}/project/{project}', function ($slug, $projectId) {
    $user = User::where('slug', $slug)->firstOrFail();
    $project = Project::where('user_id', $user->id)
        ->where('id', $projectId)
        ->firstOrFail();

    // 增加項目瀏覽數（帶防刷機制）
    $project->incrementViewsWithTracking(
        request()->ip(),
        request()->userAgent()
    );

    // 獲取用戶的公開履歷，用於頁面切換
    $resume = $user->resume()->where('is_public', true)->first();

    return view('livewire.portfolio.project-detail', [
        'user' => $user,
        'project' => $project,
        'resume' => $resume,
    ]);
})->name('portfolio.project.detail');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // 履歷管理路由
    Route::prefix('resume')->group(function () {
        Volt::route('/', 'resume.dashboard')->name('resume.dashboard');
        Volt::route('/edit', 'resume.edit')->name('resume.edit');
    });

});

require __DIR__.'/auth.php';

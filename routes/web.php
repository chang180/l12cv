<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Models\Resume;
use App\Models\User;
use App\Models\Project;
use App\Http\Controllers\RedisTestController;

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

    if (!$resume) {
        abort(404);
    }
    return view('livewire.resume.public', [
        'resume' => $resume
    ]);
})->name('resume.public');

// 公開作品集路由 - 不需要驗證
Route::get('/portfolio/{user}', function ($user) {
    $user = User::findOrFail($user);
    $projects = $user->projects()->orderBy('order')->orderBy('created_at', 'desc')->get();

    // 獲取用戶的公開履歷，用於頁面切換
    $resume = $user->resume()->where('is_public', true)->first();

    return view('livewire.portfolio.public', [
        'user' => $user,
        'projects' => $projects,
        'resume' => $resume
    ]);
})->name('portfolio.public');

// 單個作品詳情頁路由 - 不需要驗證
Route::get('/portfolio/{user}/project/{project}', function ($userId, $projectId) {
    $user = User::findOrFail($userId);
    $project = Project::where('user_id', $userId)
                    ->where('id', $projectId)
                    ->firstOrFail();

    // 獲取用戶的公開履歷，用於頁面切換
    $resume = $user->resume()->where('is_public', true)->first();

    return view('livewire.portfolio.project-detail', [
        'user' => $user,
        'project' => $project,
        'resume' => $resume
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
        Volt::route('/settings', 'resume.settings')->name('resume.settings');
    });
});

require __DIR__.'/auth.php';

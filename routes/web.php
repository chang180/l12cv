<?php

use App\Http\Controllers\ResumeBatchExportController;
use App\Http\Controllers\ResumeDocxController;
use App\Http\Controllers\ResumePdfController;
use App\Models\Project;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// 公開履歷路由 - 不需要驗證
Route::get('/@{slug}', function ($slug) {
    $resume = Resume::where('slug', $slug)
        ->where('is_public', true)
        ->with(['user.projects' => fn ($query) => $query
            ->orderByDesc('is_featured')
            ->orderBy('order')
            ->orderByDesc('created_at'),
        ])  // 預先載入用戶資料與履歷專案經驗
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

// DOCX 導出路由
Route::get('/@{slug}/docx', [ResumeDocxController::class, 'download'])->name('resume.docx');

// 批次匯出路由
Route::get('/@{slug}/exports', [ResumeBatchExportController::class, 'download'])->name('resume.exports');

// 公開作品集路由 - 不需要驗證 (使用 slug 而不是 ID)
Route::get('/p/{slug}', function ($slug) {
    $user = User::where('slug', $slug)->firstOrFail();
    $allProjects = $user->projects()->orderBy('order')->orderBy('created_at', 'desc')->get();

    $search = trim((string) request('q', ''));
    $selectedCategory = trim((string) request('category', ''));
    $selectedTag = trim((string) request('tag', ''));

    $projects = $allProjects->filter(function (Project $project) use ($search, $selectedCategory, $selectedTag) {
        if ($selectedCategory !== '' && $project->category !== $selectedCategory) {
            return false;
        }

        if ($selectedTag !== '' && ! in_array($selectedTag, $project->tags ?? [], true)) {
            return false;
        }

        if ($search === '') {
            return true;
        }

        $searchableValues = collect([
            $project->title,
            $project->description,
            $project->category,
        ])->merge($project->technologies ?? [])
            ->merge($project->tags ?? [])
            ->filter();

        return $searchableValues->contains(fn ($value) => mb_stripos((string) $value, $search) !== false);
    })->values();

    $categories = $allProjects->pluck('category')->filter()->unique()->sort()->values();
    $tags = $allProjects->flatMap(fn (Project $project) => $project->tags ?? [])->filter()->unique()->sort()->values();

    // 獲取用戶的公開履歷，用於頁面切換
    $resume = $user->resume()->where('is_public', true)->first();

    return view('livewire.portfolio.public', [
        'user' => $user,
        'projects' => $projects,
        'totalProjects' => $allProjects->count(),
        'categories' => $categories,
        'tags' => $tags,
        'search' => $search,
        'selectedCategory' => $selectedCategory,
        'selectedTag' => $selectedTag,
        'hasPortfolioFilters' => $search !== '' || $selectedCategory !== '' || $selectedTag !== '',
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

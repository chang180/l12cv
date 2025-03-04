<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Models\Resume;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

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

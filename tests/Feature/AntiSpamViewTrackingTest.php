<?php

use App\Models\User;
use App\Models\Project;
use App\Models\ViewTracking;

test('項目防刷機制正常工作', function () {
    // 創建測試用戶和項目
    $user = User::factory()->create();
    $project = Project::create([
        'user_id' => $user->id,
        'title' => 'Test Project',
        'views' => 0
    ]);

    $ipAddress = '192.168.1.2';
    $userAgent = 'Mozilla/5.0 (Test Browser)';

    // 第一次訪問應該增加瀏覽數
    $result1 = $project->incrementViewsWithTracking($ipAddress, $userAgent);
    expect($result1)->toBeTrue();
    expect($project->fresh()->views)->toBe(1);

    // 同一 IP 立即再次訪問不應該增加瀏覽數
    $result2 = $project->incrementViewsWithTracking($ipAddress, $userAgent);
    expect($result2)->toBeFalse();
    expect($project->fresh()->views)->toBe(1);

    // 驗證瀏覽記錄已創建
    expect(ViewTracking::where('trackable_type', 'project')
        ->where('trackable_id', $project->id)
        ->where('ip_address', $ipAddress)
        ->count())->toBe(1);
});

test('不同 IP 可以正常增加瀏覽數', function () {
    // 創建測試項目
    $user = User::factory()->create();
    $project = Project::create([
        'user_id' => $user->id,
        'title' => 'Test Project 2',
        'views' => 0
    ]);

    // 第一個 IP 訪問
    $result1 = $project->incrementViewsWithTracking('192.168.1.1', 'Browser 1');
    expect($result1)->toBeTrue();

    // 第二個 IP 訪問
    $result2 = $project->incrementViewsWithTracking('192.168.1.2', 'Browser 2');
    expect($result2)->toBeTrue();

    // 瀏覽數應該是 2
    expect($project->fresh()->views)->toBe(2);

    // 驗證兩個瀏覽記錄都已創建
    expect(ViewTracking::where('trackable_type', 'project')
        ->where('trackable_id', $project->id)
        ->count())->toBe(2);
});

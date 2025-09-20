# L12CV 故障排除指南

## 常見問題解決方案

### 安裝和設定問題

#### 1. Composer 安裝失敗
**問題**: 執行 `composer install` 時出現錯誤

**可能原因**:
- PHP 版本不符合要求
- 記憶體不足
- 網路連線問題

**解決方案**:
```bash
# 檢查 PHP 版本
php -v

# 增加記憶體限制
php -d memory_limit=2G /usr/local/bin/composer install

# 使用國內鏡像
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

# 清除快取後重試
composer clear-cache
composer install
```

#### 2. NPM 安裝失敗
**問題**: 執行 `npm install` 時出現錯誤

**解決方案**:
```bash
# 清除快取
npm cache clean --force

# 刪除 node_modules 重新安裝
rm -rf node_modules package-lock.json
npm install

# 使用淘寶鏡像
npm config set registry https://registry.npmmirror.com
```

#### 3. 資料庫連線問題
**問題**: Laravel 無法連線到資料庫

**檢查項目**:
```bash
# 檢查 .env 檔案設定
cat .env | grep DB_

# 檢查 SQLite 檔案是否存在
ls -la database/database.sqlite

# 測試資料庫連線
php artisan tinker
>>> DB::connection()->getPdo();
```

**解決方案**:
```bash
# 建立 SQLite 資料庫檔案
touch database/database.sqlite

# 設定正確的檔案權限
chmod 664 database/database.sqlite

# 執行遷移
php artisan migrate
```

### 開發環境問題

#### 4. 伺服器無法啟動
**問題**: `php artisan serve` 無法啟動

**解決方案**:
```bash
# 檢查端口是否被占用
netstat -tulpn | grep :8000

# 使用其他端口
php artisan serve --port=8080

# 清除快取
php artisan config:clear
php artisan cache:clear
```

#### 5. 前端建構失敗
**問題**: `npm run dev` 或 `npm run build` 失敗

**解決方案**:
```bash
# 檢查 Node.js 版本
node -v
npm -v

# 重新安裝依賴
rm -rf node_modules package-lock.json
npm install

# 檢查 Vite 配置
cat vite.config.js

# 手動建構
npx vite build
```

#### 6. Livewire 元件無法載入
**問題**: Livewire 元件無法正常運作

**檢查項目**:
```bash
# 檢查 Livewire 服務提供者
php artisan vendor:publish --tag=livewire:assets

# 檢查視圖檔案是否存在
ls -la resources/views/livewire/

# 清除視圖快取
php artisan view:clear
```

**解決方案**:
```bash
# 重新發佈 Livewire 資源
php artisan livewire:publish --assets

# 檢查元件命名是否正確
# 檔案: app/Livewire/ComponentName.php
# 視圖: resources/views/livewire/component-name.blade.php
```

### 功能相關問題

#### 7. 檔案上傳失敗
**問題**: 用戶無法上傳頭像或專案縮圖

**檢查項目**:
```bash
# 檢查儲存目錄權限
ls -la storage/app/public/
ls -la public/storage/

# 檢查檔案上傳設定
php -i | grep upload
```

**解決方案**:
```bash
# 建立符號連結
php artisan storage:link

# 設定正確權限
chmod -R 755 storage/
chmod -R 755 public/storage/

# 檢查 PHP 上傳設定
# upload_max_filesize = 2M
# post_max_size = 8M
# max_file_uploads = 20
```

#### 8. 履歷無法公開
**問題**: 設定履歷為公開後仍無法存取

**檢查項目**:
```bash
# 檢查履歷資料
php artisan tinker
>>> $resume = App\Models\Resume::find(1);
>>> $resume->is_public;

# 檢查路由快取
php artisan route:list | grep resume
```

**解決方案**:
```bash
# 清除路由快取
php artisan route:clear

# 檢查 slug 是否正確設定
# 確保 slug 欄位不為空且唯一
```

#### 9. 深色模式無法切換
**問題**: 深色模式切換按鈕無效

**檢查項目**:
```html
<!-- 檢查 Alpine.js 是否正確載入 -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- 檢查 Flux UI 是否正確載入 -->
@fluxAppearance
```

**解決方案**:
```bash
# 重新建構前端資源
npm run build

# 檢查 Alpine.js 版本
# 確保與 Flux UI 相容
```

### 效能問題

#### 10. 頁面載入緩慢
**問題**: 頁面載入時間過長

**診斷步驟**:
```bash
# 檢查資料庫查詢
# 在 app/Providers/AppServiceProvider.php 中啟用查詢日誌
DB::listen(function ($query) {
    Log::info($query->sql, $query->bindings);
});

# 檢查記憶體使用
php artisan tinker
>>> memory_get_usage(true);
```

**解決方案**:
```bash
# 使用 Eager Loading
$users = User::with('resume', 'projects')->get();

# 清除快取
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 優化資料庫查詢
php artisan migrate:status
```

#### 11. 圖片載入緩慢
**問題**: 頭像和專案縮圖載入緩慢

**解決方案**:
```bash
# 檢查圖片檔案大小
ls -lah storage/app/public/

# 實作圖片壓縮
# 在模型中使用圖片處理
```

### 安全性問題

#### 12. CSRF 令牌錯誤
**問題**: 表單提交時出現 CSRF 令牌錯誤

**檢查項目**:
```html
<!-- 確保表單包含 CSRF 令牌 -->
@csrf

<!-- 檢查 session 設定 -->
```

**解決方案**:
```bash
# 檢查 session 設定
cat config/session.php

# 清除 session 資料
php artisan session:table
php artisan migrate
```

#### 13. 檔案權限問題
**問題**: 檔案無法讀取或寫入

**解決方案**:
```bash
# 設定正確的檔案權限
find storage -type f -exec chmod 664 {} \;
find storage -type d -exec chmod 755 {} \;
find bootstrap/cache -type f -exec chmod 664 {} \;
find bootstrap/cache -type d -exec chmod 755 {} \;

# 設定正確的所有者
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

### 部署問題

#### 14. 生產環境部署失敗
**問題**: 部署到生產環境時出現錯誤

**檢查清單**:
```bash
# 環境變數設定
cat .env

# 資料庫連線測試
php artisan migrate:status

# 檔案權限檢查
ls -la storage/
ls -la bootstrap/cache/
```

**解決方案**:
```bash
# 優化應用程式
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 建構前端資源
npm run build

# 設定正確權限
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

#### 15. 靜態資源無法載入
**問題**: CSS 和 JavaScript 檔案無法載入

**解決方案**:
```bash
# 檢查 Vite 建構
npm run build

# 檢查 public/build 目錄
ls -la public/build/

# 確保 Vite 設定正確
cat vite.config.js
```

## 除錯工具和技巧

### Laravel 除錯工具

#### 1. Laravel Telescope
```bash
# 安裝 Telescope
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate

# 存取除錯面板
http://your-app.test/telescope
```

#### 2. Laravel Debugbar
```bash
# 安裝 Debugbar
composer require barryvdh/laravel-debugbar --dev

# 發布配置檔案
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
```

#### 3. 日誌檢查
```bash
# 查看應用程式日誌
tail -f storage/logs/laravel.log

# 查看特定日期的日誌
grep "2024-01-01" storage/logs/laravel.log
```

### 資料庫除錯

#### 1. 查詢日誌
```php
// 在 AppServiceProvider 中啟用查詢日誌
DB::listen(function ($query) {
    if (app()->environment('local')) {
        Log::info($query->sql, $query->bindings);
    }
});
```

#### 2. 資料庫檢查
```bash
# 檢查資料表結構
php artisan tinker
>>> Schema::getColumnListing('users');

# 檢查資料
>>> User::count();
>>> Resume::where('is_public', true)->count();
```

### 前端除錯

#### 1. 瀏覽器開發者工具
- 檢查 Console 錯誤
- 檢查 Network 請求
- 檢查 Elements 結構

#### 2. Livewire 除錯
```bash
# 啟用 Livewire 除錯模式
# 在 .env 中設定
LIVEWIRE_ASSET_URL=

# 檢查 Livewire 元件狀態
# 在瀏覽器開發者工具中查看 Livewire 請求
```

## 效能監控

### 應用程式監控

#### 1. 記憶體使用監控
```php
// 在控制器中監控記憶體使用
$memoryBefore = memory_get_usage();
// 執行操作
$memoryAfter = memory_get_usage();
Log::info('Memory usage', [
    'before' => $memoryBefore,
    'after' => $memoryAfter,
    'difference' => $memoryAfter - $memoryBefore
]);
```

#### 2. 執行時間監控
```php
// 監控執行時間
$startTime = microtime(true);
// 執行操作
$endTime = microtime(true);
Log::info('Execution time', [
    'duration' => $endTime - $startTime
]);
```

### 資料庫監控

#### 1. 慢查詢監控
```php
// 監控慢查詢
DB::listen(function ($query) {
    if ($query->time > 1000) { // 超過 1 秒
        Log::warning('Slow query detected', [
            'sql' => $query->sql,
            'bindings' => $query->bindings,
            'time' => $query->time
        ]);
    }
});
```

## 預防措施

### 定期維護

#### 1. 每日檢查
- 檢查錯誤日誌
- 監控系統效能
- 檢查磁碟空間使用

#### 2. 每週維護
- 清理快取
- 檢查資料庫效能
- 更新依賴套件

#### 3. 每月維護
- 安全更新
- 備份資料
- 效能優化

### 監控設定

#### 1. 錯誤監控
```php
// 設定錯誤處理
set_error_handler(function ($severity, $message, $file, $line) {
    Log::error('PHP Error', [
        'severity' => $severity,
        'message' => $message,
        'file' => $file,
        'line' => $line
    ]);
});
```

#### 2. 異常監控
```php
// 全域異常處理
Handler::render(function ($request, Throwable $exception) {
    Log::error('Unhandled exception', [
        'exception' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);
    
    return parent::render($request, $exception);
});
```

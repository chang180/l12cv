# L12CV 開發指南

## 開發環境設定

### 系統需求
- PHP 8.4 或更高版本
- Composer
- Node.js 18+ 和 npm
- SQLite（開發環境）

### 安裝步驟

1. **複製專案**
```bash
git clone <repository-url>
cd l12cv
```

2. **安裝 PHP 依賴**
```bash
composer install
```

3. **安裝前端依賴**
```bash
npm install
```

4. **環境設定**
```bash
cp .env.example .env
php artisan key:generate
```

5. **資料庫設定**
```bash
# 確保 SQLite 資料庫檔案存在
touch database/database.sqlite

# 執行資料庫遷移
php artisan migrate
```

6. **啟動開發伺服器**
```bash
# 使用 Composer 腳本啟動所有服務
composer run dev

# 或分別啟動
php artisan serve
npm run dev
```

## 專案結構說明

### 核心目錄結構
```
l12cv/
├── app/
│   ├── Console/Commands/          # Artisan 命令
│   ├── Http/Controllers/          # HTTP 控制器
│   ├── Livewire/                  # Livewire 元件
│   │   ├── Actions/               # 通用操作元件
│   │   └── Resume/                # 履歷相關元件
│   │       └── Portfolio/         # 作品集管理元件
│   ├── Models/                    # Eloquent 模型
│   ├── Observers/                 # 模型觀察者
│   └── Providers/                 # 服務提供者
├── database/
│   ├── migrations/                # 資料庫遷移檔案
│   ├── seeders/                   # 資料填充器
│   └── factories/                 # 模型工廠
├── resources/
│   ├── views/                     # Blade 視圖
│   │   ├── components/            # 可重用元件
│   │   ├── livewire/              # Livewire 視圖
│   │   └── layouts/               # 版面配置
│   ├── css/                       # 樣式檔案
│   └── js/                        # JavaScript 檔案
├── routes/                        # 路由定義
├── storage/                       # 儲存目錄
└── tests/                         # 測試檔案
```

## 開發規範

### 程式碼風格
- 使用 Laravel Pint 進行程式碼格式化
- 遵循 PSR-12 程式碼標準
- 使用有意義的變數和函數名稱

```bash
# 格式化程式碼
./vendor/bin/pint
```

### 命名規範

#### 檔案命名
- 模型：`PascalCase.php`（如 `User.php`）
- 控制器：`PascalCase.php`（如 `UserController.php`）
- Livewire 元件：`PascalCase.php`（如 `UserProfile.php`）
- 視圖檔案：`kebab-case.blade.php`（如 `user-profile.blade.php`）

#### 資料庫命名
- 表名：`snake_case`（如 `users`, `user_profiles`）
- 欄位名：`snake_case`（如 `first_name`, `created_at`）
- 外鍵：`{model}_id`（如 `user_id`）

#### 路由命名
- 使用描述性名稱：`users.index`, `users.store`
- 使用動詞表示動作：`create`, `edit`, `destroy`

### Git 提交規範
```
<type>(<scope>): <subject>

<body>

<footer>
```

**Type 類型**:
- `feat`: 新功能
- `fix`: 修復錯誤
- `docs`: 文件更新
- `style`: 程式碼格式化
- `refactor`: 重構
- `test`: 測試相關
- `chore`: 建構工具或輔助工具的變動

## 功能開發指南

### 新增 Livewire 元件

1. **建立元件類別**
```bash
php artisan make:livewire ComponentName
```

2. **實作元件邏輯**
```php
<?php

namespace App\Livewire;

use Livewire\Component;

class ComponentName extends Component
{
    public $property = '';
    
    public function method()
    {
        // 元件邏輯
    }
    
    public function render()
    {
        return view('livewire.component-name');
    }
}
```

3. **建立視圖檔案**
```html
<!-- resources/views/livewire/component-name.blade.php -->
<div>
    <!-- 元件內容 -->
</div>
```

### 新增資料庫遷移

1. **建立遷移檔案**
```bash
php artisan make:migration create_table_name
```

2. **定義資料表結構**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('table_name', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_name');
    }
};
```

3. **執行遷移**
```bash
php artisan migrate
```

### 新增模型

1. **建立模型**
```bash
php artisan make:model ModelName
```

2. **定義模型屬性**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelName extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
```

### 新增路由

1. **在 `routes/web.php` 中新增路由**
```php
Route::get('/path', function () {
    return view('view-name');
})->name('route.name');
```

2. **使用 Volt 元件路由**
```php
Volt::route('/path', 'component-name')->name('route.name');
```

## 測試指南

### 執行測試
```bash
# 執行所有測試
./vendor/bin/pest

# 執行特定測試檔案
./vendor/bin/pest tests/Feature/ExampleTest.php

# 執行測試並顯示覆蓋率
./vendor/bin/pest --coverage
```

### 撰寫測試

#### 功能測試範例
```php
<?php

use App\Models\User;
use App\Models\Resume;

it('can create a resume', function () {
    $user = User::factory()->create();
    
    $resume = Resume::create([
        'user_id' => $user->id,
        'title' => 'Test Resume',
        'slug' => 'test-resume',
    ]);
    
    expect($resume)->toBeInstanceOf(Resume::class);
    expect($resume->user_id)->toBe($user->id);
});
```

#### Livewire 元件測試範例
```php
<?php

use App\Livewire\Resume\Edit;
use App\Models\User;
use App\Models\Resume;

it('can update resume basic info', function () {
    $user = User::factory()->create();
    $resume = Resume::factory()->create(['user_id' => $user->id]);
    
    Livewire::actingAs($user)
        ->test(Edit::class, ['resumeId' => $resume->id])
        ->set('title', 'Updated Title')
        ->set('summary', 'Updated Summary')
        ->call('updateBasicInfo');
    
    $resume->refresh();
    expect($resume->title)->toBe('Updated Title');
    expect($resume->summary)->toBe('Updated Summary');
});
```

## 除錯指南

### Laravel 除錯工具

1. **Laravel Telescope**（開發環境）
```bash
php artisan telescope:install
php artisan migrate
```

2. **Laravel Debugbar**
```bash
composer require barryvdh/laravel-debugbar --dev
```

### 常見問題排除

#### 1. 權限問題
```bash
# 設定儲存目錄權限
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

#### 2. 快取問題
```bash
# 清除所有快取
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 3. Composer 相依性問題
```bash
# 重新安裝相依性
rm -rf vendor/
composer install
```

#### 4. Node.js 相依性問題
```bash
# 重新安裝前端相依性
rm -rf node_modules/
npm install
```

## 部署指南

### 生產環境設定

1. **環境變數設定**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

2. **優化設定**
```bash
# 產生應用程式金鑰
php artisan key:generate --force

# 執行資料庫遷移
php artisan migrate --force

# 優化應用程式
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 建構前端資源
npm run build
```

### 檔案權限設定
```bash
# 設定適當的檔案權限
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

## 效能優化

### 資料庫優化

1. **使用 Eager Loading**
```php
// 避免 N+1 問題
$users = User::with('resume', 'projects')->get();
```

2. **適當的索引**
```php
// 在遷移中新增索引
$table->index(['user_id', 'created_at']);
```

### 前端優化

1. **圖片優化**
```php
// 使用適當的圖片格式和大小
// 實作圖片壓縮和快取
```

2. **CSS/JS 壓縮**
```bash
npm run build
```

### 快取策略

1. **視圖快取**
```bash
php artisan view:cache
```

2. **路由快取**
```bash
php artisan route:cache
```

3. **配置快取**
```bash
php artisan config:cache
```

## 安全性考量

### 輸入驗證
- 使用 Laravel 驗證規則
- 清理使用者輸入
- 防止 SQL 注入

### 檔案上傳安全
```php
// 驗證檔案類型和大小
$request->validate([
    'file' => 'required|file|mimes:jpg,png|max:2048'
]);
```

### CSRF 保護
- 所有表單都包含 CSRF 令牌
- 使用 `@csrf` 指令

### XSS 防護
- 使用 Blade 的 `{{ }}` 語法自動轉義
- 謹慎使用 `{!! !!}` 語法

## 監控和日誌

### 錯誤追蹤
```php
// 記錄錯誤
Log::error('Error message', ['context' => $data]);
```

### 效能監控
```php
// 測量執行時間
$start = microtime(true);
// 執行操作
$time = microtime(true) - $start;
Log::info('Operation completed', ['duration' => $time]);
```

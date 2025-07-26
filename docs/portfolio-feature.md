# 個人作品集功能實現計劃

本文檔詳細描述了在履歷系統中添加個人作品集功能的實現計劃和步驟。

## 功能概述

個人作品集功能將允許用戶展示他們的項目作品，與履歷信息一起構成完整的個人展示系統。訪問者可以在履歷和作品集之間無縫切換，獲得更全面的求職者信息。

## 數據結構

### Projects 表結構

| 欄位名 | 類型 | 說明 |
|-------|------|------|
| id | bigint | 主鍵 |
| user_id | bigint | 外鍵，關聯到 users 表 |
| title | string | 項目標題 |
| description | text | 項目描述 |
| thumbnail | string | 項目縮略圖路徑 |
| url | string | 項目演示鏈接 |
| github_url | string | 項目 GitHub 鏈接 |
| technologies | json | 使用的技術標籤 |
| completion_date | date | 項目完成日期 |
| is_featured | boolean | 是否為特色項目 |
| order | integer | 排序順序 |
| created_at | timestamp | 創建時間 |
| updated_at | timestamp | 更新時間 |

## 模型關係

```php
// User 模型中添加
public function projects()
{
    return $this->hasMany(Project::class);
}

// Project 模型
class Project extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'thumbnail',
        'url',
        'github_url',
        'technologies',
        'completion_date',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'technologies' => 'array',
        'completion_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

## 路由設計

```php
// 公開作品集頁面
Route::get('/portfolio/{user}', Portfolio\PublicPage::class)->name('portfolio.public');

// 作品集管理頁面（需要登入）
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/portfolio', Portfolio\ManagePage::class)->name('portfolio.manage');
    Route::get('/dashboard/portfolio/create', Portfolio\CreatePage::class)->name('portfolio.create');
    Route::get('/dashboard/portfolio/{project}/edit', Portfolio\EditPage::class)->name('portfolio.edit');
});
```

## 頁面設計

### 1. 公開作品集頁面

公開作品集頁面將以網格布局展示用戶的項目作品，支持暗黑模式。

**主要元素：**
- 頁面頂部的履歷/作品集切換標籤
- 網格布局的項目卡片
- 每個卡片包含：縮略圖、標題、描述、技術標籤、鏈接

**設計注意事項：**
- 確保與履歷頁面風格一致
- 支持暗黑模式
- 響應式設計，適配不同屏幕尺寸

### 2. 作品集管理頁面

作品集管理頁面將設置於履歷管理頁面中的新增頁籤，列出用戶的所有項目，並提供添加、編輯、刪除功能。

**主要元素：**
- 項目列表
- 添加新項目按鈕
- 每個項目的編輯和刪除按鈕
- 項目排序功能

### 3. 項目創建/編輯頁面

用於創建新項目或編輯現有項目的表單頁面。

**表單字段：**
- 項目標題
- 項目描述
- 縮略圖上傳
- 項目鏈接
- GitHub 鏈接
- 技術標籤
- 完成日期
- 是否為特色項目

## 實現步驟

1. **數據庫設計**
   - 創建 projects 表的遷移文件
   - 運行遷移

2. **模型創建**
   - 創建 Project 模型
   - 設置與 User 模型的關聯關係

3. **後台管理功能**
   - 創建作品集管理頁面
   - 實現項目的 CRUD 操作
   - 添加縮略圖上傳功能
   - 實現項目排序功能

4. **前台展示功能**
   - 修改履歷頁面，添加切換到作品集的標籤
   - 創建作品集公開頁面
   - 實現響應式網格布局
   - 確保暗黑模式支持

5. **優化與測試**
   - 確保所有功能正常運行
   - 優化用戶體驗
   - 測試不同屏幕尺寸的顯示效果
   - 測試暗黑模式的顯示效果

## 暗黑模式適配

為確保在暗黑模式下的良好顯示效果，我們需要注意以下幾點：

1. **背景色與文字色**
   - 普通模式：白色背景，深色文字
   - 暗黑模式：深色背景，淺色文字

2. **卡片與容器**
   - 普通模式：淺灰色或白色背景，淺色邊框
   - 暗黑模式：深灰色背景，深色邊框

3. **強調色**
   - 使用主題色（primary color）作為強調色，確保在兩種模式下都有足夠的對比度

4. **圖標與按鈕**
   - 確保圖標在暗黑模式下有足夠的可見度
   - 按鈕使用適當的背景色和文字色

## 示例代碼

### 作品集卡片組件

```html
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
    @if($project->thumbnail)
        <img src="{{ Storage::url($project->thumbnail) }}" alt="{{ $project->title }}" class="w-full h-48 object-cover">
    @else
        <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
    @endif
    
    <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $project->title }}</h3>
        
        @if($project->technologies)
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($project->technologies as $tech)
                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs rounded-md">{{ $tech }}</span>
                @endforeach
            </div>
        @endif
        
        <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3 mb-4">{{ $project->description }}</p>
        
        <div class="flex space-x-3">
            @if($project->url)
                <a href="{{ $project->url }}" target="_blank" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium">
                    查看演示
                </a>
            @endif
            
            @if($project->github_url)
                <a href="{{ $project->github_url }}" target="_blank" class="text-gray-600 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300 text-sm font-medium">
                    GitHub
                </a>
            @endif
        </div>
    </div>
</div>
```

### 頁面切換導航

```html
<div class="border-b border-gray-200 dark:border-gray-700 mb-6">
    <nav class="flex space-x-8">
        <a href="{{ route('resume.public', $resume->slug) }}" 
           class="{{ request()->routeIs('resume.public') ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            履歷
        </a>
        <a href="{{ route('portfolio.public', $resume->user->id) }}" 
           class="{{ request()->routeIs('portfolio.public') ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
            作品集
        </a>
    </nav>
</div>
```

## 總結

個人作品集功能將大大增強用戶的個人展示能力，使他們能夠更全面地展示自己的技能和經驗。通過與履歷功能的無縫集成，我們將提供一個完整的個人品牌展示平台。

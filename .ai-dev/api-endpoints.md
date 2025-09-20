# L12CV API 端點文件

## API 概覽

L12CV 主要使用 Livewire 提供互動式 API，結合傳統 HTTP 路由處理靜態頁面。本文檔詳細說明所有可用的端點和它們的功能。

## 公開路由（無需認證）

### 1. 首頁
```
GET /
```
**用途**: 顯示歡迎頁面
**回應**: `welcome.blade.php` 視圖

### 2. 履歷展示頁面
```
GET /@{slug}
```
**參數**:
- `slug` (string): 履歷的友善 URL 標識符

**用途**: 顯示公開的履歷頁面
**條件**: 履歷必須設定為公開 (`is_public = true`)
**回應**: `livewire.resume.public` 視圖

**範例**:
```
GET /@john-doe
```

### 3. 作品集展示頁面
```
GET /p/{slug}
```
**參數**:
- `slug` (string): 用戶的友善 URL 標識符

**用途**: 顯示用戶的公開作品集
**回應**: `livewire.portfolio.public` 視圖

**範例**:
```
GET /p/john-doe
```

### 4. 單個作品詳情頁
```
GET /p/{slug}/project/{project}
```
**參數**:
- `slug` (string): 用戶的友善 URL 標識符
- `project` (int): 專案 ID

**用途**: 顯示單個專案的詳細資訊
**回應**: `livewire.portfolio.project-detail` 視圖

**範例**:
```
GET /p/john-doe/project/123
```

### 5. Redis 測試端點
```
GET /test-redis
GET /redis-use-cases
GET /credit-card-cache
```
**用途**: Redis 功能測試和演示
**控制器**: `RedisTestController`

## 認證路由（需要登入）

### 設定相關

#### 1. 設定首頁（重定向）
```
GET /settings
```
**用途**: 重定向到個人資料設定頁面
**重定向**: `/settings/profile`

#### 2. 個人資料設定
```
GET /settings/profile
```
**用途**: 顯示和編輯個人資料
**元件**: `settings.profile` (Volt)

#### 3. 密碼設定
```
GET /settings/password
```
**用途**: 變更密碼
**元件**: `settings.password` (Volt)

#### 4. 外觀設定
```
GET /settings/appearance
```
**用途**: 調整介面外觀（深色模式等）
**元件**: `settings.appearance` (Volt)

### 履歷管理

#### 1. 履歷控制台
```
GET /resume
```
**用途**: 履歷管理首頁
**元件**: `resume.dashboard` (Volt)

#### 2. 編輯履歷
```
GET /resume/edit
```
**用途**: 編輯履歷內容
**元件**: `resume.edit` (Volt)

#### 3. 履歷設定
```
GET /resume/settings
```
**用途**: 履歷公開設定和 URL 管理
**元件**: `resume.settings` (Volt)

## Livewire 元件 API

### 履歷編輯元件 (`App\Livewire\Resume\Edit`)

#### 公開方法
- `updateBasicInfo()`: 更新基本資料（標題、摘要）
- `addEducation()`: 新增學歷項目
- `removeEducation($index)`: 移除指定學歷項目
- `updateEducation()`: 更新學歷資料
- `addExperience()`: 新增工作經驗
- `removeExperience($index)`: 移除指定工作經驗
- `updateExperience()`: 更新工作經驗

#### 屬性
- `$resumeId`: 履歷 ID
- `$resume`: 履歷模型實例
- `$title`: 履歷標題
- `$summary`: 履歷摘要
- `$education`: 學歷陣列
- `$experience`: 經驗陣列
- `$currentTab`: 當前編輯分頁

### 專案表單元件 (`App\Livewire\Resume\Portfolio\ProjectForm`)

#### 公開方法
- `openProjectForm($params)`: 開啟專案表單
- `closeProjectForm()`: 關閉專案表單
- `loadProject($projectId)`: 載入現有專案資料
- `resetForm()`: 重置表單
- `updatedThumbnail()`: 縮圖上傳處理
- `removeThumbnail()`: 移除縮圖
- `saveProject()`: 儲存專案

#### 屬性
- `$resumeId`: 履歷 ID
- `$projectId`: 專案 ID（編輯時使用）
- `$title`: 專案標題
- `$description`: 專案描述
- `$thumbnail`: 縮圖檔案
- `$url`: 專案連結
- `$githubUrl`: GitHub 連結
- `$technologies`: 技術標籤（逗號分隔）
- `$completionDate`: 完成日期
- `$isFeatured`: 是否為特色專案
- `$order`: 排序順序

#### 驗證規則
```php
[
    'title' => 'required|max:255',
    'description' => 'nullable',
    'thumbnail' => 'nullable|image|max:1024',
    'url' => 'nullable|url|max:255',
    'githubUrl' => 'nullable|url|max:255',
    'technologies' => 'nullable',
    'completionDate' => 'nullable|date',
    'isFeatured' => 'boolean',
    'order' => 'integer|min:0',
]
```

### 專案列表元件 (`App\Livewire\Resume\Portfolio\ProjectList`)

#### 公開方法
- `refreshProjects()`: 重新載入專案列表
- `createProject()`: 建立新專案
- `editProject($projectId)`: 編輯專案
- `confirmDelete($projectId)`: 確認刪除專案

#### 屬性
- `$resumeId`: 履歷 ID
- `$projects`: 專案列表

### 專案刪除確認元件 (`App\Livewire\Resume\Portfolio\DeleteConfirmation`)

#### 公開方法
- `confirmDelete($projectId)`: 確認刪除
- `deleteProject()`: 執行刪除操作
- `cancelDelete()`: 取消刪除

## Volt 元件 API

### 履歷控制台 (`resume.dashboard`)

#### 公開函數
- `edit()`: 導向編輯頁面
- `settings()`: 導向設定頁面
- `updateVisibility($value)`: 更新公開狀態
- `create()`: 建立新履歷

#### 狀態
- `$resume`: 當前履歷
- `$isPublic`: 公開狀態

### 設定頁面元件

#### 個人資料設定 (`settings.profile`)
- 用戶基本資料編輯
- 頭像上傳功能

#### 密碼設定 (`settings.password`)
- 密碼變更功能
- 密碼強度驗證

#### 外觀設定 (`settings.appearance`)
- 深色模式切換
- 主題設定

## 事件系統

### Livewire 事件

#### 全域事件
- `notify`: 顯示通知訊息
  - `message`: 訊息內容
  - `type`: 訊息類型（success, error, warning, info）

#### 專案相關事件
- `projectSaved`: 專案儲存完成
- `projectDeleted`: 專案刪除完成
- `openProjectForm`: 開啟專案表單
- `closeProjectForm`: 關閉專案表單
- `openDeleteModal`: 開啟刪除確認對話框

### 事件監聽器

#### 專案表單元件
```php
protected $listeners = [
    'openProjectForm', 
    'closeProjectForm'
];
```

#### 專案列表元件
```php
protected $listeners = [
    'projectSaved' => 'refreshProjects', 
    'projectDeleted' => 'refreshProjects'
];
```

## 認證系統

### 認證路由
包含在 `routes/auth.php` 中的 Laravel Breeze 認證路由：
- `GET /login`: 登入頁面
- `POST /login`: 處理登入
- `GET /register`: 註冊頁面
- `POST /register`: 處理註冊
- `POST /logout`: 登出
- `GET /forgot-password`: 忘記密碼
- `POST /forgot-password`: 處理忘記密碼
- `GET /reset-password`: 重設密碼
- `POST /reset-password`: 處理重設密碼

### 中間件保護
- `auth`: 需要登入的路由
- `guest`: 僅限未登入用戶的路由

## 錯誤處理

### HTTP 錯誤
- `404 Not Found`: 履歷或專案不存在
- `403 Forbidden`: 無權限存取
- `500 Internal Server Error`: 伺服器錯誤

### 驗證錯誤
- 表單驗證失敗時顯示錯誤訊息
- 檔案上傳錯誤處理
- 資料庫操作錯誤處理

## 回應格式

### HTML 回應
大部分端點返回 HTML 視圖，使用 Blade 模板引擎渲染。

### JSON 回應（Livewire）
Livewire 元件透過 AJAX 請求返回 JSON 格式的狀態更新。

### 重定向
某些操作完成後會重定向到相關頁面：
- 成功建立履歷後重定向到編輯頁面
- 登入後重定向到控制台
- 登出後重定向到首頁

## 效能考量

### 快取策略
- 視圖快取
- 路由快取
- 配置快取

### 查詢優化
- 使用 Eager Loading 避免 N+1 問題
- 適當的資料庫索引
- 分頁查詢

### 檔案處理
- 圖片壓縮
- 檔案大小限制
- 適當的儲存策略

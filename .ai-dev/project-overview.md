# L12CV 專案總覽

## 專案簡介

L12CV 是一個基於 Laravel 的個人履歷和作品集平台，讓使用者能夠輕鬆建立和分享專業履歷以及展示專案作品。這是一個現代化的全端應用程式，使用 Livewire 提供互動式用戶體驗。

## 技術架構

### 後端技術
- **PHP 8.4** - 最新版本的 PHP 語言
- **Laravel 12.x** - PHP 網頁框架
- **Livewire 3.x** - 互動式前端框架
- **Volt** - Laravel Livewire 的新語法
- **SQLite** - 資料庫（開發環境）
- **Filament** - 管理面板框架
- **Laravel Boost** - 開發工具和 MCP 伺服器

### 前端技術
- **Tailwind CSS 4.x** - CSS 框架
- **Flux UI** - 現代化 UI 元件庫
- **Alpine.js** - 輕量級 JavaScript 框架
- **Vite** - 前端建構工具

### 開發工具
- **Pest** - PHP 測試框架
- **Laravel Pint** - 程式碼風格檢查
- **Laravel Sail** - Docker 開發環境

## 核心功能

### 1. 用戶管理
- 用戶註冊、登入、登出
- 個人資料管理
- 頭像上傳功能
- 使用者 slug 自動生成

### 2. 履歷管理
- 建立和編輯履歷
- 基本資料（標題、摘要）
- 學歷背景管理
- 工作經驗管理
- 公開/私人狀態設定
- 友善 URL 分享（@username）

### 3. 作品集管理
- 專案 CRUD 操作
- 專案縮圖上傳
- 技術標籤管理
- 專案連結（演示、GitHub）
- 專案排序功能
- 特色專案標記

### 4. 公開展示
- 響應式履歷展示頁面
- 響應式作品集展示頁面
- 深色模式支援
- SEO 友善的 URL 結構

## 專案結構

```
l12cv/
├── app/
│   ├── Http/Controllers/     # HTTP 控制器
│   ├── Livewire/            # Livewire 元件
│   │   ├── Actions/         # 通用操作
│   │   └── Resume/          # 履歷相關元件
│   │       └── Portfolio/   # 作品集管理元件
│   ├── Models/              # 資料模型
│   ├── Observers/           # 模型觀察者
│   └── Providers/           # 服務提供者
├── database/
│   ├── migrations/          # 資料庫遷移
│   └── seeders/            # 資料填充
├── resources/
│   ├── views/
│   │   ├── components/      # Blade 元件
│   │   ├── livewire/        # Livewire 視圖
│   │   └── layouts/         # 版面配置
│   ├── css/                # 樣式檔案
│   └── js/                 # JavaScript 檔案
└── routes/                  # 路由定義
```

## 資料庫設計

### Users 表
- 基本用戶資訊
- slug 欄位用於友善 URL
- avatar 欄位用於頭像

### Resumes 表
- 履歷基本資料
- JSON 格式儲存學歷和經驗
- is_public 控制公開狀態

### Projects 表
- 作品集專案資料
- 支援多媒體檔案
- 技術標籤和排序功能

## 路由結構

### 公開路由
- `/@{slug}` - 履歷展示頁面
- `/p/{slug}` - 作品集展示頁面
- `/p/{slug}/project/{id}` - 單個作品詳情頁

### 認證路由
- `/resume/*` - 履歷管理
- `/settings/*` - 用戶設定
- `/dashboard` - 控制台

## 開發特色

1. **現代化開發體驗**
   - 使用 Volt 語法簡化 Livewire 開發
   - 響應式設計支援多種裝置
   - 深色模式完整支援

2. **用戶體驗優化**
   - 友善的 URL 結構
   - 即時預覽功能
   - 直觀的管理介面

3. **可擴展性**
   - 模組化元件設計
   - 清晰的資料關聯
   - 標準化的 API 結構

## 部署考量

- 支援傳統 PHP 環境
- 可與 Docker 整合
- 靜態資源透過 Vite 優化
- 檔案上傳支援本地和雲端儲存

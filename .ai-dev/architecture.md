# L12CV 系統架構文件

## 整體架構概覽

L12CV 採用現代化的 MVC 架構，結合 Livewire 提供互動式用戶體驗。系統設計遵循 Laravel 最佳實踐，具有良好的可維護性和擴展性。

## 核心架構模式

### 1. MVC + Livewire 混合架構
```
Controller Layer (HTTP Controllers)
    ↓
Livewire Components (業務邏輯)
    ↓
Model Layer (資料存取)
    ↓
Database Layer (SQLite)
```

### 2. 元件化設計
- **Blade Components**: 可重用的 UI 元件
- **Livewire Components**: 互動式業務邏輯元件
- **Volt Components**: 簡化的 Livewire 語法

## 資料流架構

### 用戶請求流程
1. **路由層** (`routes/web.php`)
   - 公開路由：直接渲染視圖
   - 認證路由：透過中間件保護

2. **控制器層** (HTTP Controllers)
   - 處理傳統 HTTP 請求
   - Redis 測試功能

3. **Livewire 層**
   - 處理 AJAX 請求
   - 管理元件狀態
   - 即時 UI 更新

4. **模型層**
   - 資料驗證
   - 業務邏輯
   - 資料庫互動

## 資料模型架構

### 核心實體關係
```
User (1) ←→ (1) Resume
User (1) ←→ (N) Project
```

### 模型職責分工

#### User Model
- 用戶身份驗證
- 個人資料管理
- 頭像處理
- Slug 自動生成

#### Resume Model
- 履歷資料管理
- JSON 欄位處理（education, experience）
- 公開狀態控制

#### Project Model
- 作品集專案管理
- 檔案上傳處理
- 技術標籤管理
- 排序和特色功能

## 前端架構

### 技術堆疊
```
Tailwind CSS (樣式框架)
    ↓
Flux UI (元件庫)
    ↓
Alpine.js (互動邏輯)
    ↓
Livewire (狀態管理)
```

### 視圖層次結構
```
layouts/app.blade.php (主版面)
    ↓
livewire/components (業務元件)
    ↓
components/ (可重用元件)
```

### 響應式設計策略
- **Mobile First**: 優先考慮行動裝置
- **Breakpoints**: 
  - `sm`: 640px+
  - `md`: 768px+
  - `lg`: 1024px+
  - `xl`: 1280px+

## 狀態管理架構

### Livewire 狀態管理
1. **元件狀態**
   - 表單資料
   - UI 狀態
   - 載入狀態

2. **全域狀態**
   - 用戶認證狀態
   - 主題設定
   - 通知系統

### 資料持久化
- **Session**: 用戶會話資料
- **Database**: 持久化資料
- **Storage**: 檔案上傳

## 安全性架構

### 認證與授權
- **Laravel Sanctum**: API 認證
- **Middleware**: 路由保護
- **Policy**: 資料存取控制

### 資料保護
- **CSRF Protection**: 跨站請求偽造防護
- **XSS Protection**: 跨站腳本攻擊防護
- **SQL Injection**: 參數化查詢

### 檔案上傳安全
- **檔案類型驗證**
- **檔案大小限制**
- **儲存路徑隔離**

## 效能優化架構

### 資料庫優化
- **Eager Loading**: 預先載入關聯資料
- **索引優化**: 關鍵欄位索引
- **查詢優化**: N+1 問題防範

### 前端優化
- **Vite 建構**: 現代化建構工具
- **CSS 壓縮**: 樣式檔案優化
- **JavaScript 分塊**: 程式碼分割

### 快取策略
- **View Caching**: 視圖快取
- **Route Caching**: 路由快取
- **Config Caching**: 配置快取

## 擴展性設計

### 模組化架構
- **Service Providers**: 服務註冊
- **Facades**: 靜態介面
- **Contracts**: 介面契約

### 事件系統
- **Model Events**: 模型事件
- **Custom Events**: 自訂事件
- **Listeners**: 事件監聽器

### 可配置性
- **Environment Variables**: 環境變數
- **Configuration Files**: 配置檔案
- **Feature Flags**: 功能開關

## 部署架構

### 開發環境
- **Laravel Sail**: Docker 開發環境
- **Hot Reload**: 即時重載
- **Debug Tools**: 除錯工具

### 生產環境
- **Web Server**: Nginx/Apache
- **PHP-FPM**: PHP 處理器
- **Database**: MySQL/PostgreSQL
- **File Storage**: 本地/雲端儲存

## 監控與日誌

### 應用程式監控
- **Laravel Telescope**: 開發除錯
- **Error Tracking**: 錯誤追蹤
- **Performance Monitoring**: 效能監控

### 日誌系統
- **Application Logs**: 應用程式日誌
- **Error Logs**: 錯誤日誌
- **Access Logs**: 存取日誌

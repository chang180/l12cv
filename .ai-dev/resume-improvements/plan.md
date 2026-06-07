# 履歷顯示與列印改進計畫

## 背景與目標

公開履歷的下載（PDF / DOCX / ZIP）格式尚未達標，需暫時隱藏；工作經驗應最新在前；編輯頁工作描述需支援 Markdown；列印時避免同一項目跨頁斷開。

## 不在本次範圍

- PDF/DOCX 頭像、中文字型、Markdown 渲染修復
- 公開頁改用 `avatarUrl()`（Google 頭像）

## 實作項目

### 1. 隱藏下載按鈕

- `config/resume.php`：`downloads_enabled` 預設 `false`
- `public.blade.php`：以 `@if (config('resume.downloads_enabled'))` 包住 PDF / DOCX / 下載全部
- 保留列印按鈕與既有 routes

### 2. 工作經驗排序

- `App\Support\ResumeExperience::sort()`
- 套用：公開頁、編輯頁 mount / updateExperience
- 公開頁工作描述改 Markdown 渲染

### 3. 工作描述編輯

- 編輯頁 experience 欄位使用可自動增高的 `textarea`（`wire:model` 直接綁定）
- 基本資料簡介仍使用泛化後的 `MarkdownEditor`（`parentEvent`、`@script` 動態載入）

### 3b. 編輯頁儲存提示與公開預覽

- `statusMessage` + Alpine 淡出（基本資料 / 學歷 / 工作經驗等）
- `MarkdownHelper` `soft_break` 修正公開預覽頁簡介換行

### 4. 列印增強

- `app.css`：`print-item`、`print-section-header`，section 級 avoid 改 auto
- `public.blade.php`：各原子項目加 class

## 變更檔案

- `config/resume.php`
- `app/Support/ResumeExperience.php`
- `app/Livewire/Resume/MarkdownEditor.php`
- `resources/views/livewire/resume/public.blade.php`
- `resources/views/livewire/resume/edit.blade.php`
- `resources/css/app.css`
- `tests/Feature/ResumeExperienceTest.php`
- `tests/Feature/PostUpgradeWalkthroughTest.php`

## 驗收標準

- [ ] 預設不顯示下載 PDF / DOCX / 下載全部，列印仍可用
- [ ] 工作經驗依日期最新在前（含 current）
- [ ] 編輯頁工作描述為可自動增高 textarea；簡介為 Markdown 編輯器
- [ ] 公開預覽頁簡介單行換行正常
- [ ] 公開頁 HTML 含 `print-item`、`print-section-header`
- [ ] `php artisan test tests/Feature/ResumeExperienceTest.php` 等通過

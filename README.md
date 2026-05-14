# L13CV

L13CV 是一個以 Laravel、Livewire、Volt 和 Flux UI 建立的個人履歷與作品集平台。使用者可以建立履歷、管理作品集、公開分享個人展示頁，並追蹤履歷與作品瀏覽統計。

## 主要功能

- 履歷建立、編輯、公開/私人狀態切換
- 內建履歷模板選擇、公開頁套版與 PDF 套版
- 技能標籤管理、公開展示與 PDF 匯出
- 語言能力管理、公開展示與 PDF 匯出
- 證照和認證管理、公開展示與 PDF 匯出
- 專案經驗整合到公開履歷與 PDF 匯出
- 公開履歷列印模式與 A4 版面優化
- DOCX 履歷匯出
- 作品集專案 CRUD、縮圖上傳、技術標籤與排序
- 公開履歷頁、公開作品集頁、專案詳情頁
- 中文 PDF / DOCX 履歷匯出
- 基於 IP 的 24 小時瀏覽防刷統計
- Google 登入、註冊與既有帳號綁定
- 深色模式與繁體中文設定介面

## 技術棧

- PHP 8.4
- Laravel 13
- Laravel Socialite
- Laravel Sanctum
- Livewire 4、Volt、Flux UI
- Tailwind CSS 4、Alpine.js、Vite
- Pest 4、PHPUnit 12
- TCPDF

## 本地開發

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

啟動服務：

```bash
composer run dev
```

或分開執行：

```bash
php artisan serve
npm run dev
```

若使用 Laravel Herd，本機網址為 `https://l12cv.test`。

## Google OAuth

本機開發預設關閉 Google OAuth，避免沒有公開 callback URL 或 Google Cloud 憑證時阻斷登入測試。未啟用時，登入、註冊與設定頁會顯示繁體中文提示，並保留電子郵件登入流程。

要啟用 Google OAuth：

```env
GOOGLE_AUTH_ENABLED=true
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

既有帳號不會在未登入狀態下被 Google email 自動併入。使用者必須先用原本的電子郵件與密碼登入，再到「帳號設定」綁定 Google 帳號。

## 驗證

```bash
composer validate
php artisan --version
php artisan route:list
php artisan test
npm run build
```

## 文件

- `.ai-dev/upgrade/plan.md`：本次 Laravel 13 與 Google OAuth 升級計畫
- `.ai-dev/upgrade/progress.md`：實作進度
- `.ai-dev/upgrade/verification.md`：驗證紀錄
- `.ai-dev/resume-template-system.md`：履歷模板系統實作規格與驗收紀錄
- `docs/portfolio-feature.md`：作品集功能規劃與背景

## 授權

此專案採用 MIT 授權，詳見 [LICENSE](LICENSE)。

# L13CV 驗證紀錄

## 已執行

- `composer update -W`
  - Laravel Framework 升級至 13.8.0。
  - Laravel Socialite 安裝至 5.27.0。
  - Filament 相依移除。
- `composer install`
  - autoload 與 package discovery 成功。
- `php artisan --version`
  - 輸出：Laravel Framework 13.8.0。
- `composer validate`
  - 通過。
- 2026-05-14 Herd / HTTP check via `https://l12cv.test/@test-user/docx`
  - DOCX 回應 200，`Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document`。
  - 下載檔案可被辨識為 Microsoft Word 2007+，`word/document.xml` 含有姓名、技能、證照與專案經驗內容。
- 2026-05-14 Herd / HTTP check via `https://l12cv.test/@test-user/exports`
  - ZIP 回應 200，`Content-Type: application/zip`。
  - 下載檔案可被辨識為 Zip archive，內含 `resume.pdf` 與 `resume.docx`。
- 2026-05-14 Herd / HTTP check via `https://l12cv.test/p/test-user`
  - 公開作品集顯示 `音訊展示`。
  - 專案詳情頁顯示 `多媒體展示`，並輸出 `<audio>` 與 `https://example.com/demo.mp3` 來源。
  - 公開作品集與專案詳情頁顯示分類 `網站平台`。
- `php artisan route:list --except-vendor`
  - Google OAuth、公開履歷、作品集、設定解除綁定等專案路由可解析。
- `php artisan migrate --force`
  - 已套用 `personal_access_tokens` 與 Google OAuth 欄位遷移。
- `php artisan test`
  - 36 passed，100 assertions。
- `npm run build`
  - Vite build 成功。
- Browser check via `http://host.docker.internal:8000`
  - `/`、`/login`、`/register` 可載入，頁面標題與品牌為 `L13CV`。
  - 登入與註冊頁在本機模式顯示 Google OAuth 停用提示。
  - 未再出現密碼顯示切換相關的 Alpine console error。
- 2026-05-14 Herd / HTTP check via `https://l12cv.test`
  - `/login`、`/@test-user`、`/p/test-user-TWs4B`、`/@test-user/pdf` 回應 200。
  - PDF 回應 `Content-Type: application/pdf`，檔名使用 UTF-8 `filename*`。
- `php artisan test tests/Feature/PostUpgradeWalkthroughTest.php`
  - 3 passed，23 assertions。
  - 覆蓋登入後 `/resume`、`/resume/edit`、`/settings/profile`，公開履歷、公開作品集、專案詳情、PDF 下載，以及 Google OAuth 本機停用提示。
- `php artisan test tests/Feature/ResumeUpdateTest.php tests/Feature/PostUpgradeWalkthroughTest.php`
  - 10 passed，77 assertions。
  - 覆蓋履歷模板更新、無效模板驗證、公開頁模板標記、無效模板 fallback、`classic`、`modern`、`compact` 三種模板 PDF 回應、技能標籤清理/儲存/公開展示、語言能力清理/儲存/公開展示、證照和認證清理/儲存/公開展示、專案經驗在公開履歷展示、公開履歷列印入口、DOCX 下載回應、ZIP 批次下載回應、作品集音訊展示，以及作品集分類展示。
- `php artisan test`
  - 45 passed，174 assertions。
- `npm run build`
  - Vite build 成功，產出 `public/build/assets/app-DxrL9WTu.css`。
- `composer validate`
  - 通過。

## 待執行

- 真實 Google OAuth callback 測試：需要可用的 Google Cloud OAuth client、公開 callback URL，並將 `GOOGLE_AUTH_ENABLED=true`。

# L13CV 升級進度

## 已完成

- 建立 `.ai-dev/upgrade` 計畫與進度文件。
- Composer 相依已升級至 Laravel 13.8.0、Laravel Socialite 5.27.0、Pest 4、PHPUnit 12。
- 移除未使用的 Filament 相依與 `filament:upgrade` composer script。
- 修復 `php artisan` 啟動阻斷點，Sanctum vendor 已恢復。
- 新增 Google OAuth controller、路由、設定、資料欄位與測試。
- 本機開發預設 `GOOGLE_AUTH_ENABLED=false`，登入、註冊、設定頁都有停用提示。
- Google 新註冊帳號使用隨機密碼，解除綁定前必須先設定密碼。
- 品牌改為 `l13cv` / `L13CV`，主要設定與認證頁英文文案改為繁體中文。
- README 已更新為 Laravel 13 與 Google OAuth 現況。
- 完整資料庫遷移已執行。
- `composer validate`、`php artisan route:list`、`php artisan test`、`npm run build` 已通過。
- 已用瀏覽器檢查首頁、登入頁、註冊頁，確認 L13CV 品牌與本機 Google OAuth 停用提示。
- 已補上升級後 walkthrough 回歸測試，涵蓋 `/resume`、`/resume/edit`、`/settings/profile`、公開履歷、公開作品集、專案詳情與 PDF 下載。
- 修復履歷編輯頁在 Laravel 13 / Livewire 4 下可能回落到 `layouts::app` 的佈局解析問題。
- PDF 下載改為先產生 PDF 字串，再交由 Laravel response 輸出，避免測試流程直接印出 PDF 二進位內容。
- `.ai-dev/resume-template-system.md` 已建立，作為下一階段履歷模板系統實作規格。
- 履歷模板系統第一版已完成，包含 `classic`、`modern`、`compact` 內建模板、編輯頁選擇、公開履歷套版與 PDF 主題。
- 技能標籤系統第一版已完成，包含 `resumes.skills`、編輯頁管理、公開頁標籤展示與 PDF 匯出。
- 語言能力管理第一版已完成，包含 `resumes.languages`、編輯頁管理、公開頁展示與 PDF 匯出。
- 證照和認證管理第一版已完成，包含 `resumes.certifications`、編輯頁管理、公開頁展示與 PDF 匯出。
- 專案經驗整合到履歷第一版已完成，公開履歷與 PDF 會載入使用者作品集專案並依精選、排序、建立時間顯示。

## 待驗證

- 真實 Google OAuth callback 仍需外部 callback URL 與 Google Cloud 憑證，尚未列入本機完成條件。

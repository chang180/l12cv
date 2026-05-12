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

## 待驗證

- 以登入帳號補看履歷管理、履歷編輯、設定頁與公開作品集互動細節。

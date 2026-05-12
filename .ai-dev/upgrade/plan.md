# L13CV 升級與 Google 帳號計畫

## 目標

- 將 Laravel 12 專案升級至 Laravel 13，repo 名稱維持 `l12cv`。
- 應用名稱改為 `l13cv`，使用者可見品牌統一為 `L13CV`。
- 新增 Google 註冊、登入，以及既有帳號登入後綁定 Google 帳號。
- 本機開發預設關閉 Google OAuth，避免缺少外部 callback 與憑證時阻斷測試。
- 同步更新 README、`.ai-dev` 文件與驗證紀錄。

## 實作策略

- 相依升級至 Laravel 13、Laravel Boost 2、Laravel Tinker 3、Pest 4、PHPUnit 12，移除未使用的 Filament 相依與 `filament:upgrade` script。
- 保留 Sanctum，修復原先因 vendor 缺少 Sanctum trait 導致 `php artisan` 無法啟動的問題。
- 新增 `laravel/socialite`，以 `services.google.*` 控制 Google OAuth 設定。
- `GOOGLE_AUTH_ENABLED=false` 作為本機預設；未啟用時 UI 顯示提示，controller 也阻止 OAuth redirect/callback。
- `users` 新增 `google_id`、`google_avatar`、`google_linked_at`、`password_set_at`。
- Google 新註冊帳號使用不可知隨機密碼，`password_set_at=null`；解除 Google 綁定前必須先設定密碼。
- Google email 已存在且未登入時，不自動併入既有帳號，提示先用原帳號登入再綁定。

## 文件同步

- README 記錄 Laravel 13、Google OAuth、本機停用策略與驗證指令。
- `.ai-dev/upgrade/progress.md` 持續記錄完成項與待辦。
- `.ai-dev/upgrade/verification.md` 記錄實際執行的 composer、artisan、test、build 結果。

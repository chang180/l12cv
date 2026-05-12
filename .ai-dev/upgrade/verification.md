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

## 待執行

- 以登入帳號進一步檢查 `/resume`、`/resume/edit`、`/settings/profile`、公開履歷與作品集頁的互動細節。

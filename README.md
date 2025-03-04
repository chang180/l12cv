# Resume Builder

一個簡潔的個人履歷製作平台，讓你輕鬆建立和分享專業履歷。

## 主要功能

- 📝 快速建立個人履歷
- 🔗 自訂分享連結（例如：@your-name）
- 🌓 支援深色模式
- 📱 完整的響應式設計
- 🔒 隱私控制，自由設定公開狀態
- 🇹🇼 繁體中文介面

## 開發技術

- [Laravel](https://laravel.com) - PHP 網頁框架
- [Livewire](https://livewire.laravel.com) - 互動式前端框架
- [Volt](https://livewire.laravel.com/docs/volt) - Laravel Livewire 的新寫法
- [Tailwind CSS](https://tailwindcss.com) - CSS 框架
- [Alpine.js](https://alpinejs.dev) - 輕量級 JS 框架

## 本地開發

1. 安裝相依套件
```bash
composer install
npm install
```

2. 環境設定
```bash
cp .env.example .env
php artisan key:generate
```

3. 資料庫設定
```bash
php artisan migrate
```

4. 啟動開發環境
```bash
php artisan serve
npm run dev
```

## 專案結構

```
resume-builder/
├── app/
│   ├── Http/
│   │   └── Livewire/    # Livewire 元件
│   └── Models/          # 資料模型
├── resources/
│   └── views/
│       ├── components/  # Blade 元件
│       └── livewire/    # Livewire 視圖
└── routes/              # 路由定義
```

## 開源協作

這是一個開源專案，歡迎提出建議或貢獻程式碼：

1. Fork 此專案
2. 建立新分支
3. 提交修改
4. 發送 Pull Request

## 授權條款

此專案採用 MIT 授權 - 詳見 [LICENSE](LICENSE) 檔案

## 關於作者

這是一個用於展示的個人專案，目的是分享程式開發經驗和技術交流。如果你有任何問題或建議，歡迎透過 Issues 聯繫。

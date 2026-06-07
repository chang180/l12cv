# 履歷顯示與列印改進進度

## 已完成

- 建立 `.ai-dev/resume-improvements/plan.md` 與本進度文件
- 新增 `config/resume.php`，`downloads_enabled` 預設 `false`（`RESUME_DOWNLOADS_ENABLED` 可重新開啟）
- 公開履歷頁隱藏 PDF / DOCX / 下載全部按鈕，保留列印
- 新增 `App\Support\ResumeExperience::sort()`，最新工作在前（`current` 優先，其餘依 `end_date` 降序）
- 公開頁、編輯頁 mount、儲存工作經驗時套用排序
- 公開頁工作描述改以 `MarkdownHelper` 渲染（純文字亦支援換行）
- 編輯頁**基本資料簡介**保留 Toast UI Markdown 編輯器（`@script` 動態初始化）
- 編輯頁**工作描述**改為可自動增高的 `textarea`（穩定優先，不再用 Markdown 子元件）
- 編輯頁各分頁儲存成功提示：`statusMessage` + 4 秒淡出（取代無效的 session flash / notify）
- `MarkdownHelper` 設定 `soft_break => <br />`，公開預覽頁簡介單行換行正常顯示
- 列印 CSS 增強：`print-item`、`print-section-header`，section 級 `break-inside: auto`
- 公開履歷各區塊加上 `print-item` / `print-section-header` 標記
- 設定頁表單補齊 `autocomplete`（密碼、刪除帳號）

## 測試紀錄

```bash
php artisan test tests/Feature/ResumeExperienceTest.php tests/Feature/ResumeEditPreviewTest.php tests/Feature/ResumeUpdateTest.php tests/Feature/PostUpgradeWalkthroughTest.php
```

結果：ResumeExperience 7、ResumeEditPreview 4、ResumeUpdate 8、PostUpgradeWalkthrough 通過

## 手動驗證（建議）

- 公開履歷 → 列印預覽 → 確認每筆學歷/工作卡片不在中間被切開
- 編輯頁各分頁儲存 → 頂部綠色提示 + 約 4 秒後淡出
- 儀表板「預覽履歷」→ 簡介多行（✔ 條列）換行正常
- 編輯頁「工作經驗」→ 新增項目後工作描述 textarea 可見且可自動增高

## 待後續

- PDF/DOCX 頭像、中文字型、Markdown 內容一致性
- 公開頁頭像改用 `User::avatarUrl()`
- 格式確認後設 `RESUME_DOWNLOADS_ENABLED=true` 重新開放下載

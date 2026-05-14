# 履歷模板系統實作規格

## 狀態

第一版已於 2026-05-14 完成。

- `classic`、`modern`、`compact` 三種內建模板已集中定義於 `App\Support\ResumeTemplates`。
- `resumes.template` 已新增，既有與無效模板 key 會 fallback 到 `classic`。
- `/resume/edit` 已提供模板選擇卡片。
- `@{slug}` 公開履歷頁會依模板套用頁面背景、卡片、區塊色系與 `data-resume-template` 標記。
- `@{slug}/pdf` 會依模板套用 PDF 色系、標題對齊與區塊順序。
- 回歸測試已覆蓋模板更新、無效模板驗證、公開頁 fallback 與三種模板 PDF 下載。

## 目標

建立第一版履歷模板系統，讓使用者可以在履歷編輯流程中選擇內建模板，並讓公開履歷頁與 PDF 匯出使用一致的版面語意。第一版聚焦可預覽、可切換、可回歸測試，不做自訂模板編輯器。

## 第一版範圍

- 內建模板：至少提供 `classic`、`modern`、`compact` 三種固定模板。
- 模板選擇：在 `/resume/edit` 增加模板選擇區塊，儲存到使用者履歷。
- 模板預覽：在編輯頁顯示模板名稱、用途說明與縮略預覽，不需要即時完整 iframe 預覽。
- 公開履歷：`/@{slug}` 依履歷模板套用對應 Blade partial 或 class map。
- PDF 匯出：`/@{slug}/pdf` 使用同一個模板 key 決定 PDF 標題、區塊順序與基本樣式。
- 預設值：既有履歷若沒有模板欄位，視為 `classic`。

## 資料與介面

- `resumes` 新增 `template` 字串欄位，預設 `classic`。
- `Resume` model 將 `template` 加入 `$fillable`。
- 模板選項集中定義於單一 PHP helper 或 enum-like class，避免 Blade、Livewire 與 PDF controller 各自硬編 key。
- 無效模板 key 一律 fallback 到 `classic`，並在表單驗證層限制可選值。

## 測試與驗收

- Feature test：既有履歷沒有模板欄位時，公開頁與 PDF 仍可正常產生。
- Livewire test：使用者可以在履歷編輯頁更新模板，無效 key 會被拒絕。
- Feature test：公開履歷頁會依模板 key 呈現對應樣式標記或模板名稱。
- Feature test：PDF 下載在三種模板下都回應 200 與 `application/pdf`。
- 文件同步：完成實作時更新 `README.md`、`.ai-dev/features-roadmap.md` 與本規格。

## 暫不納入

- 自訂模板編輯器。
- 模板 marketplace 或使用者上傳模板。
- DOCX 匯出。
- 多語系模板內容。

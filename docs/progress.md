# 執行進度

> Source of truth。Slack `[status]` 僅鏡像本檔。

## 目前狀態

| 欄位 | 值 |
|------|-----|
| **Workflow** | v4 local-first |
| **Phase** | bootstrap complete |
| **狀態** | ready |
| **Branch** | `main` |
| **Slack 產品頻道** | `#10-proj-l12cv`（`C0B47UBS2HH`） |
| **Repo** | `chang180/l12cv` |
| **Claude Slack thread** | `1779200466.309049`（legacy；v4 本機優先） |
| **Slack progress thread** | （建議 Pin `[status-thread]` 後填入 `thread_ts`） |
| **最後更新** | 2026-06-07 |

## 進行中任務

| Task | Status | Executor | Notes |
|------|--------|----------|-------|
| T-001-bootstrap-v4 | done | cursor | skills v4 portability E2E |
| T-002-slack-status-sync | done | cursor | MCP 發/讀/搜 [status] 通過 |

## 里程碑

- [x] ai-orchestrator skill bootstrap（v3）
- [x] Claude Code web session 綁定 `chang180/l12cv`
- [x] **v4 skills** 複製 + `docs/WORKFLOW-v4.md`、tasks、rules
- [x] T-001 完成 + push
- [x] 首則 v4 Slack `[status]`（無 @ bot）
- [ ] Pin v4 charter（`docs/slack-handoff-template.md` §2）

## Claude Code on the web（選用）

- [ ] Cloud Environment setup script（見 2026-05-19 清單）
- v4 不以 Slack `[handoff]` 為預設派工路徑

## 例外紀錄

| 日期 | 類型 | 說明 |
|------|------|------|
| — | — | — |

## 日誌

### 2026-06-07

- **履歷顯示與列印改進**（`.ai-dev/resume-improvements/`）— 已 commit：
  - 預設隱藏公開履歷 PDF/DOCX/ZIP 下載（`config/resume.php`），保留列印
  - 工作經驗最新在前（`ResumeExperience::sort()`）
  - 列印 CSS `print-item` / `print-section-header`
  - 編輯頁儲存提示（`statusMessage` 淡出）、學歷/工作經驗/基本資料 UX 修正
  - 工作描述改 textarea；簡介保留 Markdown 編輯器；公開預覽 `soft_break` 換行
  - 測試：`ResumeExperienceTest`、`ResumeEditPreviewTest`、`ResumeUpdateTest`、`PostUpgradeWalkthroughTest`

### 2026-05-20

- 升級 ai-orchestrator skills v4（local-first）。
- Bootstrap：`WORKFLOW-v4.md`、`docs/tasks/`、orchestrator.mdc v4。
- **Slack [status] 可讀性 E2E**：發送 + `slack_read_channel` + `slack_search_public` 通過（T-002）。

### 2026-05-19

- v3 bootstrap；Claude E2E #8；Slack 編排公告。

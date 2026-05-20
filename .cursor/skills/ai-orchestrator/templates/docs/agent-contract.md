# Agent 執行契約（v4 local-first）

主編排者：**Cursor IDE**。完整流程見 [WORKFLOW-v4.md](WORKFLOW-v4.md)。

## 角色定義

### ChatGPT（advisory）

- **可做**：PRD、任務拆解、驗收標準。
- **不可做**：直接寫入 repo（除非人工轉貼或匯入 `docs/tasks/`）。

### Cursor IDE（orchestrator / 主執行）

- **可做**：
  - 讀寫 `docs/progress.md`、`docs/tasks/*.md`
  - `gh`：issue、branch、PR
  - Slack MCP：發 `[status]`、`[decision]`（**不**預設 @ bot）
- **不可做**：
  - 假設 Slack Workflow YAML 自動部署
  - 假設 MCP 可 `/invite` bot
  - 預設 Slack `[handoff]` execute

### Cursor Cloud（`@Cursor`）

- **v4 預設**：**不用於派工**。
- **觸發**（僅例外）：人工在 `#21-agent-cursor` 或記錄 `exception: slack-delegate` 時。
- **風險**：`handoff-complete` @Cursor 可能誤開 PR。

### Claude / Codex / GitHub Copilot（Slack bots）

- **v4 預設**：**不在產品頻道派工**。
- **本機替代**：Claude Code、Cursor subagent、Copilot CLI / IDE。
- **Slack on_demand**：僅 `exception: slack-delegate`；見 optional PROTOCOL-v3。

### Slack Workflow Builder

- **角色**：可選入口（shortcut、表單）→ 產出需求文字；由人貼入 `docs/tasks/` 或 `[feature]`。
- **executor**：`manual`。

## 步驟契約（v4）

| Step ID | Action | Executor | 輸入 | 輸出 |
|---------|--------|----------|------|------|
| `plan` | 撰寫/更新規格 | human / cursor | 需求 | `docs/plan.md`（可選） |
| `task` | 建立任務檔 | cursor | 驗收條件 | `docs/tasks/T-*.md` |
| `execute` | 本機實作 | cursor / claude-code / subagent | task 檔 | code + commit |
| `progress` | 更新進度 | cursor | 狀態 | `docs/progress.md` commit |
| `mirror` | Slack 鏡像 | cursor | progress URL | `[status]` 訊息 |
| `pr` | 開 PR / 合併 | cursor + `gh` | branch | PR URL |

## 任務文件（取代 Slack handoff 正文）

1. `docs/tasks/T-xxx.md` 含 Context、Executor、Acceptance、Status。
2. **git push** 後才視為可執行/可鏡像。
3. 編排者在本機依 `Executor` 欄位派工（非 Slack mention）。

## Slack 規則

1. 產品頻道預設只發 `[status]`、`[feature]`、`[decision]`。
2. `[status]` **不得**含 `@Cursor`、`@codex`、`@claude`、`@github`。
3. Legacy `[handoff]` 僅見 optional PROTOCOL-v3 + 例外紀錄。

## 完成定義

- 任務檔 `Status: done`
- `docs/progress.md` 已更新並 push
- PR 合併或明確標記 blocked
- 可選：`[status]` 已鏡像至 Slack

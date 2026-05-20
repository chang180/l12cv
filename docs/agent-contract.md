# Agent 執行契約（v4 local-first）

主編排者：**Cursor IDE**。完整流程見 [WORKFLOW-v4.md](WORKFLOW-v4.md)。

## 角色定義

### Cursor IDE（orchestrator / 主執行）

- **可做**：讀寫 `docs/progress.md`、`docs/tasks/*.md`；`gh`；Slack `[status]`（不 @ bot）
- **不可做**：預設 Slack `[handoff]` execute

### Cursor Cloud（`@Cursor`）

- **v4 預設**：**不用於派工**

### Claude / Codex / GitHub Copilot（Slack bots）

- **v4 預設**：**不在產品頻道派工**
- **本機**：Claude Code、Cursor subagent

## 步驟契約（v4）

| Step | Action | Executor |
|------|--------|----------|
| `task` | 建立任務檔 | cursor |
| `execute` | 本機實作 | cursor / claude-code |
| `progress` | 更新進度 | cursor |
| `mirror` | Slack `[status]` | cursor |

## 完成定義

- 任務檔 `Status: done`
- `docs/progress.md` 已 push
- 可選：`[status]` 已鏡像

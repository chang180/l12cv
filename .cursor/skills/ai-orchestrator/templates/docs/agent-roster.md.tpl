# Agent Roster

Slack workspace 內可調用的 bot／整合角色。Cursor orchestrator 執行 handoff 時依此表 `@mention`。

**Workspace**：`{{SLACK_WORKSPACE}}`  
**本 repo 產品頻道**：`#{{SLACK_PROJECT_CHANNEL}}`（`{{SLACK_PROJECT_CHANNEL_ID}}`）  
**清查**：`slack_list_channel_members(channel_id, include_bots=true)`

## 頻道對照（本專案）

| 頻道 | Channel ID | 用途 |
|------|------------|------|
| `#{{SLACK_PROJECT_CHANNEL}}` | `{{SLACK_PROJECT_CHANNEL_ID}}` | **本 repo** 產品／E2E |

Agent 專線（若使用 devstream-core 分層）：`#20-agent-claude`、`#21-agent-cursor`、`#22-agent-codex`、`#30-dev-github` — 見 slack-orchestrator skill 或 `docs/slack-channel-taxonomy.md`。

## Roster（bot user_id）

> 以下為 **devstream-core** 預設；新 workspace 請用 MCP 復掃後覆寫。

| 名稱 | Role | Slack user_id | Mention | 專屬頻道 | channel_status |
|------|------|---------------|---------|----------|----------------|
| owner | owner | （填寫） | | — | joined |
| **Cursor** | orchestrator (cloud) | `U09H5GMRSEQ` | `<@U09H5GMRSEQ>` / `@Cursor` | `#21-agent-cursor` | （復掃） |
| Cursor IDE Agent | orchestrator (local) | — | Slack MCP | — | n/a |
| **Claude** | reviewer | `U0B404P284S` | `<@U0B404P284S>` | `#20-agent-claude` | （復掃） |
| **GitHub** | integration + Copilot | `U0B3VUN3QA1` | `<@U0B3VUN3QA1>` | `#30-dev-github` | （復掃） |
| **Codex** | executor | `U0B411CESCR` | `<@U0B411CESCR>` | `#22-agent-codex` | （復掃） |

## 預設 handoff

1. `<@U0B404P284S>` Claude → `#20-agent-claude`（審閱）
2. `<@U0B411CESCR>` Codex → `#22-agent-codex`（實作）
3. `<@U0B3VUN3QA1>` Copilot → `#30-dev-github` 或產品頻道（on_demand）

## 調用規則

1. 編排與 repo 主控僅 Cursor IDE Agent。
2. 同一任務勿同時 @ `@Cursor` 與 `<@U0B3VUN3QA1>` 做重複實作。
3. `notify_agents` 前查 `channel_status`（`include_bots: true`）。

## 更新紀錄

| 日期 | 變更 |
|------|------|
| {{DATE}} | bootstrap 自 ai-orchestrator skill |

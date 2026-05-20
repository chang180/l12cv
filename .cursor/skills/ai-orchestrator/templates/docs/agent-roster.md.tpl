# Agent Roster

**Workspace**：`{{SLACK_WORKSPACE}}`  
**本 repo 產品頻道**：`#{{SLACK_PROJECT_CHANNEL}}`（`{{SLACK_PROJECT_CHANNEL_ID}}`）  
**政策（v4）**：預設 **local_only** — 不在產品頻道 Slack execute 派工。

**清查**：`slack_list_channel_members(channel_id, include_bots=true)`

## 頻道對照（本專案）

| 頻道 | Channel ID | 用途（v4） |
|------|------------|------------|
| `#{{SLACK_PROJECT_CHANNEL}}` | `{{SLACK_PROJECT_CHANNEL_ID}}` | **進度看板** — `[status]`、`[feature]` |

Workspace 分層（devstream-core 等）：`#20-agent-*` 預設閒置；`#30-dev-github` 通知；`#42-knowledge-decisions` 決策鏡像 — 見 `slack-orchestrator` 或 `docs/slack-channel-taxonomy.md`。

## Roster（bot user_id）

> 以下為 **devstream-core** 預設；新 workspace 請 MCP 復掃後覆寫。v4 **不預設** @ 下列 bot 派工。

| 名稱 | Role | Slack user_id | Mention | 專屬頻道 | v4 預設 |
|------|------|---------------|---------|----------|---------|
| owner | owner | （填寫） | | — | — |
| **Cursor** | cloud agent | `U09H5GMRSEQ` | `<@U09H5GMRSEQ>` | `#21-agent-cursor` | **不用** |
| Cursor IDE | orchestrator (local) | — | Slack MCP | — | **主控** |
| **Claude** | reviewer | `U0B404P284S` | `<@U0B404P284S>` | `#20-agent-claude` | 本機優先 |
| **GitHub** | integration + Copilot | `U0B3VUN3QA1` | `<@U0B3VUN3QA1>` | `#30-dev-github` | on_demand only |
| **Codex** | executor | `U0B411CESCR` | `<@U0B411CESCR>` | `#22-agent-codex` | **不用** |

## 調用規則（v4）

1. 編排與 repo 主控僅 **Cursor IDE**。
2. 進度以 `docs/progress.md` 為準；Slack 只鏡像 `[status]`。
3. Slack execute 僅在 `progress.md` 記錄 `exception: slack-delegate` 時（見 optional PROTOCOL-v3）。

## 更新紀錄

| 日期 | 變更 |
|------|------|
| {{DATE}} | bootstrap v4 local-first |

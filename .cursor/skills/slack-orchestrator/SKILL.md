---
name: slack-orchestrator
description: >-
  Slack workspace governance for AI Engineering OS v4. Product channels are progress
  dashboards [status]; agent channels idle by default. Bootstrap taxonomy and charters.
  Use with ai-orchestrator; copy skill only to new projects.
---

# Slack Orchestrator（v4）

**只需複製本 skill**；`docs/slack-channel-taxonomy.md` 用 [bootstrap-workspace.md](bootstrap-workspace.md) 產生。

## v4 頻道角色

| Layer | 用途（v4） |
|-------|------------|
| `10-proj-*` / `11-proj-*` | **進度看板** — `[status]`、`[feature]`；本機編排 |
| `20-agent-*` | **預設閒置**；非預設派工 |
| `30-dev-*` | GitHub 通知、發版、缺陷（被動） |
| `40-*` / `42-*` | 知識、決策鏡像 |
| `99-*` | 孵化 → 升級為 L1 |

## 訊息契約

- 產品：`[feature]` `[bug]` `[release]` `[ux]` `[infra]` · **`[status]`** **`[decision]`**
- ~~`[handoff]`~~：預設禁用（legacy 見 ai-orchestrator optional PROTOCOL-v3）
- 決策：`Decision:` / `Why:` / `Tradeoff:`

Charter 模板：`templates/charter-product.md.tpl`（含「本機編排、不 @ bot」）

## 何時啟用

- 新 workspace / Layer 頻道稽核
- 新專案開 `#10-proj-*` + Pin charter
- 與 ai-orchestrator bootstrap 搭配

## Bootstrap

[bootstrap-workspace.md](bootstrap-workspace.md) → `docs/slack-channel-taxonomy.md`

## MCP

`include_bots: true` · 無法 `/invite` / rename → 人工

## 與 ai-orchestrator

| Skill | 產生 |
|-------|------|
| ai-orchestrator | `WORKFLOW-v4`、roster、progress、tasks、rules |
| slack-orchestrator | taxonomy、charter 訊息 |

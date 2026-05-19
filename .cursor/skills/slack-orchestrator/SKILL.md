---
name: slack-orchestrator
description: >-
  Slack workspace governance for AI Engineering OS. Bootstrap docs/slack-channel-taxonomy
  from templates. Channel audit, charters, taxonomy. Use with ai-orchestrator; copy
  skill only to new projects—do not copy docs.
---

# Slack Orchestrator

**只需複製本 skill**；`docs/slack-channel-taxonomy.md` 用 [bootstrap-workspace.md](bootstrap-workspace.md) 產生。

## 何時啟用

- 新 workspace / 新 Layer 頻道稽核
- 發 Channel charter（模板：`templates/charter-product.md.tpl`）
- 與 ai-orchestrator bootstrap 搭配

## Bootstrap 工作區文件

見 [bootstrap-workspace.md](bootstrap-workspace.md) → 產生 `docs/slack-channel-taxonomy.md`

## 頻道分層（摘要）

| Layer | 用途 |
|-------|------|
| 0 `00-*` | 通用 |
| 1 `10-proj-*` / `11-proj-*` | 產品 |
| 2 `20-agent-*` | handoff bus |
| 3 `30-dev-*` | 工程 |
| 4 `40-knowledge-*` | 知識 |
| 9 `99-*` | 孵化 |

## 訊息契約

- 產品：`[feature]` `[bug]` `[release]` `[ux]` `[infra]`
- Agent：`[ask]` `[handoff]` `[handoff-complete]`
- 決策：`Decision:` / `Why:` / `Tradeoff:`

Handoff 全文：目標 repo 的 `docs/slack-handoff-template.md`（ai-orchestrator bootstrap）

## MCP

`include_bots: true` · 無法 `/invite` / rename / 頻道區段 → 人工

能力邊界：目標 repo `docs/slack-capability-matrix.md`（ai-orchestrator bootstrap 內建模板）

## 與 ai-orchestrator

| Skill | 產生 |
|-------|------|
| ai-orchestrator | `rules`、roster、PROTOCOL、handoff 模板、progress |
| slack-orchestrator | `slack-channel-taxonomy`（可選）、charter 訊息 |

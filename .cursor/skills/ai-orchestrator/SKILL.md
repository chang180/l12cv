---
name: ai-orchestrator
description: >-
  Cursor-led AI orchestration: bootstrap rules+docs from skill templates only (copy
  skills folder to new projects). Slack MCP, gh, multi-agent handoff. Use for orchestrator,
  handoff-complete, bootstrap, agent roster, or applying this demo pattern.
---

# AI Orchestrator（Cursor 主控）

**Cursor IDE Agent** 為唯一主 orchestrator。  
**其他專案只需複製 `.cursor/skills/`**；rules 與 docs 用 [bootstrap.md](bootstrap.md) 在目標 repo **重建**。

## 新專案（必讀）

1. 複製 `ai-orchestrator/`（+ 建議 `slack-orchestrator/`）→ 見 [INSTALL.md](INSTALL.md)
2. 執行 [bootstrap.md](bootstrap.md) → 產生 `.cursor/rules/orchestrator.mdc` + `docs/**`
3. 人工：Slack invite、charter、@Cursor settings
4. 第一次 E2E：下方標準執行順序

模板目錄：`.cursor/skills/ai-orchestrator/templates/`（manifest.json 清單）

## 何時啟用

- bootstrap / 初始化編排文件
- Slack → GitHub → Slack 回報
- 多 agent **handoff**（先 push 任務文件）
- 關鍵字：orchestrator、handoff-complete、notify_handoff

## 核心原則

1. **Repo 主控**：issue / branch / `docs/progress.md` 僅 IDE + `gh`。
2. **`notify_agents` = notify_handoff**：不是自動 sub-agent 佇列。
3. **先 push 再 Slack**：任務 `.md` 須在 GitHub 上。
4. **Slack MCP**：`include_bots: true`。
5. **Workflow YAML** 不會自動部署到 Slack。

## Handoff

| 要做的事 | 做法 |
|----------|------|
| 派 Codex / @github | 步驟化模板 + `branch=` + zh-TW（見 `docs/slack-handoff-template.md` §4b） |
| 派 Claude | review-only，thread 內審閱 |
| 收工 | `slack_read_thread` → `handoff-complete` → `gh pr diff` |

精簡版：[handoff-protocol.md](handoff-protocol.md) · 產生後完整版：`docs/agent-handoff/PROTOCOL-v3.md`

## 標準執行順序

```
- [ ] 1. slack_read_thread / slack_read_channel
- [ ] 2. 摘要需求與驗收
- [ ] 3. gh issue create（可選）
- [ ] 4. branch + docs/agent-handoff/tasks/*
- [ ] 5. git push
- [ ] 6. Slack handoff（zh-TW）
- [ ] 7. handoff-complete → 複審 → progress.md
```

## 角色速查（devstream-core 預設 user_id）

| 角色 | Mention |
|------|---------|
| Cursor Cloud | `@Cursor` / `<@U09H5GMRSEQ>` |
| Claude | `<@U0B404P284S>` |
| Codex | `<@U0B411CESCR>` |
| GitHub Copilot | `<@U0B3VUN3QA1>` |

實際以 bootstrap 後的 `docs/agent-roster.md` 為準。

## 文件（bootstrap 後出現於目標 repo）

| 路徑 | 用途 |
|------|------|
| `docs/agent-roster.md` | 頻道 ID、分派 |
| `docs/agent-handoff/PROTOCOL-v3.md` | Handoff 協定 |
| `docs/slack-handoff-template.md` | Slack 模板 |
| `docs/progress.md` | 進度 |

## 相關 skill

- [slack-orchestrator](../slack-orchestrator/SKILL.md) — 工作區頻道治理、charter

## 陷阱

[reference-slack-limits.md](reference-slack-limits.md)

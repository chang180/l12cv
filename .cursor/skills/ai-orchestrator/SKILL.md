---
name: ai-orchestrator
description: >-
  Cursor-led v4 local-first orchestration: copy skills only, bootstrap rules+docs.
  GitHub docs/tasks progress SoT, Slack [status] mirror. Use for bootstrap, orchestrator,
  WORKFLOW-v4, portable skills to new projects.
---

# AI Orchestrator（v4 本機優先）

**Cursor IDE** 為唯一主編排者。  
**其他專案只需複製 `.cursor/skills/`**；`docs/` 與 `rules` 用 [bootstrap.md](bootstrap.md) **生成**（勿複製 demo `docs/`）。

可攜驗收：[PORTABILITY.md](PORTABILITY.md) · v3 升級：[MIGRATION-v3-to-v4.md](MIGRATION-v3-to-v4.md)

## 新專案（必讀）

1. `cp -R` 整個 `.cursor/skills/` → 見 [INSTALL.md](INSTALL.md)
2. [bootstrap.md](bootstrap.md) → `docs/WORKFLOW-v4.md`、`.cursor/rules/orchestrator.mdc`
3. 人工：開 `#10-proj-*`、Pin charter（可零 bot）
4. E2E：本機 task → push `progress.md` → Slack `[status]`（無 @ bot）

模板：`templates/manifest.json`（v2.0.0 · v4-local-first）

## 何時啟用

- bootstrap / 初始化編排文件
- 更新 `docs/progress.md`、`docs/tasks/*.md`
- Slack `[status]` 鏡像進度
- 關鍵字：orchestrator、WORKFLOW-v4、local-first、`[status]`

## 核心原則（v4）

1. **Repo SoT**：`docs/progress.md` + `docs/tasks/*.md`；IDE + `gh` 更新。
2. **本機執行**：預設不在 Slack `[handoff]` @ bot。
3. **Slack**：產品頻道 = 進度看板（`[status]`、`[decision]`）。
4. **先 push 再鏡像**：任務與進度須在 GitHub 上再發 Slack。
5. **Cloud @Cursor**：預設關閉；legacy 見 optional PROTOCOL-v3。

## 標準執行順序

```
- [ ] 1. 讀 docs/WORKFLOW-v4.md、progress.md、相關 tasks/*.md
- [ ] 2. 摘要需求與驗收（可選 gh issue）
- [ ] 3. 建立/更新 docs/tasks/T-xxx.md
- [ ] 4. 本機實作（Cursor / Claude Code / subagent）
- [ ] 5. 更新 progress.md + task status → git push
- [ ] 6. [可選] Slack [status] 至產品頻道（勿 @ bot）
- [ ] 7. gh pr → 複審 → 再 [status]
```

精簡版：[workflow-v4.md](workflow-v4.md) · 完整版：bootstrap 後 `docs/WORKFLOW-v4.md`

## 文件（bootstrap 後）

| 路徑 | 用途 |
|------|------|
| `docs/WORKFLOW-v4.md` | 主協定 |
| `docs/progress.md` | 進度 SoT |
| `docs/tasks/` | 任務檔 |
| `docs/slack-handoff-template.md` | `[status]` 模板 |
| `docs/agent-roster.md` | 頻道 / bot（v4 不預設派工） |
| `docs/agent-handoff/PROTOCOL-v3.md` | optional legacy |

## 相關 skill

- [slack-orchestrator](../slack-orchestrator/SKILL.md) — 頻道治理、charter

## 陷阱

[reference-slack-limits.md](reference-slack-limits.md)

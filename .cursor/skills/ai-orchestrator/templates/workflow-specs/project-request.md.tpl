# Workflow Runbook — {{GITHUB_REPO}}-request

> **契約檔** `.workflow-specs/{{GITHUB_REPO}}-request.yml` 不會被 Slack 自動部署。

## 專案

- Repo：`{{GITHUB_FULL_REPO}}`
- 頻道：`#{{SLACK_PROJECT_CHANNEL}}`（`{{SLACK_PROJECT_CHANNEL_ID}}`）

## Phase B（人工）

在 `#{{SLACK_PROJECT_CHANNEL}}`：

```
/invite @Claude
/invite @Cursor
/invite @Codex
/invite @github
```

`@Cursor settings` → default repo = `{{GITHUB_FULL_REPO}}`

## Workflow Builder（可選）

1. 建立 shortcut `/{{GITHUB_REPO}}-request`
2. 限制頻道：`#{{SLACK_PROJECT_CHANNEL}}`
3. 步驟：發訊息到頻道開 thread

## 編排

由 **Cursor IDE** + Slack MCP + `gh` 執行；見 `docs/agent-contract.md`。

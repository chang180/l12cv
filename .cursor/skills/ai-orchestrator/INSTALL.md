# 安裝：只複製 Skills

> **總覽**：[../README.md](../README.md) — 複製整個 `.cursor/skills/` 即可。

其他專案（如 **l12cv**）**只需**複製 skills；`rules` 與 `docs` 由 Agent 在目標 repo **bootstrap 產生**。

## 1. 複製（必做）

```bash
cp -R /path/to/ai-orchestrator-workflow-demo/.cursor/skills /path/to/your-project/.cursor/
```

**不要複製**：`docs/`、`.cursor/rules/`、`.worktrees/`（demo 專用或將由 bootstrap 產生）。

## 2. Bootstrap（在目標 repo 對 Cursor 說）

```text
請依 ai-orchestrator skill 的 bootstrap.md，為本 repo 建立 orchestrator rules 與 docs。
Slack 產品頻道：#10-proj-l12cv（請用 MCP 查 channel_id）。
```

Agent 會：

1. 讀 `.cursor/skills/ai-orchestrator/templates/manifest.json`
2. 替換 `{{GITHUB_*}}`、`{{SLACK_*}}` 變數
3. 寫入 `.cursor/rules/orchestrator.mdc` 與 `docs/**`
4. 可選執行 `slack-orchestrator/bootstrap-workspace.md`

## 3. 人工收尾

- `/invite` bots、Pin charter、`@Cursor settings`、GitHub App login
- 第一次 E2E：`[feature]` → push task → `[handoff]`

## 本 demo repo 的定位

| 內容 | 角色 |
|------|------|
| `.cursor/skills/**` | **可攜帶原始碼**（唯一需要 copy 的） |
| `docs/`、`.cursor/rules/` | **living demo 實例**（含 E2E 歷史，非必複製） |
| `.workflow-specs/` | 可選；bootstrap 可產生 |
| `.worktrees/` | 勿複製 |

## 更新 skill

```bash
cp -R "$DEMO/.cursor/skills/ai-orchestrator" "$TARGET/.cursor/skills/"
# 已 bootstrap 的 docs 不會自動更新；重大協定變更時可重新 bootstrap 或手動 merge
```

## 全域安裝（可選）

```bash
cp -R "$DEMO/.cursor/skills/ai-orchestrator" ~/.cursor/skills/
```

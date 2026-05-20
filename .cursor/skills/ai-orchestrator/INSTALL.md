# 安裝：只複製 Skills（v4）

> **總覽**：[../README.md](../README.md)

其他專案**只需**複製 `.cursor/skills/`；`rules` 與 `docs` 由 **bootstrap 產生**。

## 1. 複製（必做）

```bash
DEMO=/path/to/ai-orchestrator-workflow-demo
TARGET=/path/to/your-project

mkdir -p "$TARGET/.cursor"
cp -R "$DEMO/.cursor/skills" "$TARGET/.cursor/"
```

**不要複製**：demo 的 `docs/`、`.cursor/rules/`、`.worktrees/`。

## 2. Bootstrap（在目標 repo 對 Cursor 說）

```text
請依 ai-orchestrator/bootstrap.md 為本 repo 初始化 v4 orchestrator。
Slack 產品頻道：#10-proj-你的專案（請用 MCP 查 channel_id）。
```

Agent 會依 `templates/manifest.json` v2.0.0 寫入 `docs/WORKFLOW-v4.md` 等。

## 3. 人工收尾

- 開 / 確認 `#10-proj-*` 或 `#11-proj-*`
- Pin v4 charter（見 bootstrap 後 `docs/slack-handoff-template.md` §2）
- **可零 bot**；若需 GitHub 通知再 `/invite @github`
- **勿**預設 `@Cursor settings` 用於產品頻道派工

## 4. 第一次 E2E（v4）

1. 建立 `docs/tasks/T-001-bootstrap-verify.md`（自 `_TEMPLATE.md`）
2. 更新 `docs/progress.md` → commit → push
3. Slack 發 `[status]`（**無** @ bot）

驗收清單：[PORTABILITY.md](PORTABILITY.md)

## 本 demo repo

| 內容 | 角色 |
|------|------|
| `.cursor/skills/**` | **可攜原始碼**（唯一要 copy） |
| `docs/`、`.cursor/rules/` | living demo；非必複製 |

## 更新 skill

```bash
cp -R "$DEMO/.cursor/skills/ai-orchestrator" "$TARGET/.cursor/skills/"
cp -R "$DEMO/.cursor/skills/slack-orchestrator" "$TARGET/.cursor/skills/"  # 可選
```

已 bootstrap 的 `docs/` 不會自動更新 → [MIGRATION-v3-to-v4.md](MIGRATION-v3-to-v4.md)

## 全域安裝（可選）

見 [INSTALL.md](INSTALL.md) 文末或 skill 原始完整版（若存在 `~/.cursor/skills` 路徑說明）。

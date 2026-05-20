# Cursor Skills — AI Orchestrator（v4 local-first）

**帶到新專案只需要複製本目錄。**

```bash
cp -R /path/to/ai-orchestrator-workflow-demo/.cursor/skills /path/to/your-project/.cursor/
```

不必複製 demo repo 的 `docs/`、`.cursor/rules/`。  
在目標專案對 Cursor 執行 **bootstrap** 產生 v4 文件。

可攜驗收：[ai-orchestrator/PORTABILITY.md](ai-orchestrator/PORTABILITY.md)  
v3 升級：[ai-orchestrator/MIGRATION-v3-to-v4.md](ai-orchestrator/MIGRATION-v3-to-v4.md)

---

## 目錄

| Skill | 用途 |
|-------|------|
| [**ai-orchestrator/**](ai-orchestrator/) | v4 本機編排、bootstrap、`docs/WORKFLOW-v4.md` |
| [**slack-orchestrator/**](slack-orchestrator/) | Slack 頻道治理、v4 charter（可選） |

---

## 三步驟

### 1. 複製 skills

```bash
DEMO=/path/to/ai-orchestrator-workflow-demo
TARGET=/path/to/your-project

mkdir -p "$TARGET/.cursor"
cp -R "$DEMO/.cursor/skills" "$TARGET/.cursor/"
```

### 2. Bootstrap（在目標專案開 Cursor）

```text
請依 ai-orchestrator/bootstrap.md 初始化本 repo v4 orchestrator。
Slack 產品頻道：#10-proj-你的專案（請用 MCP 查 channel_id）。
```

產物含：`docs/WORKFLOW-v4.md`、`docs/progress.md`、`docs/tasks/_TEMPLATE.md`、`.cursor/rules/orchestrator.mdc`（見 `manifest.json` v2.0.0）

### 3. 人工收尾

- 開 / 確認產品頻道、Pin v4 charter
- 第一筆本機 `docs/tasks/T-001-*.md` + push `progress.md`
- Slack 發 `[status]`（**勿 @ bot**）

---

## v4 與 v3 差異（摘要）

| | v3 | v4 |
|---|----|-----|
| 派工 | Slack `[handoff]` | 本機 `docs/tasks/*.md` |
| 進度 | 掃 thread | `docs/progress.md` |
| Slack | execute | **`[status]`** 鏡像 |

---

## 不要複製什麼

| 路徑 | 原因 |
|------|------|
| `docs/` | bootstrap 在目標 repo 產生 |
| `.cursor/rules/` | 同上 |
| `.worktrees/` | demo 本機實驗 |

---

## 本 demo repo

`ai-orchestrator-workflow-demo` 根目錄 `docs/` 是 **living demo**（含 E2E 歷史），供對照；**不是**可攜包的一部分。

---

## 詳細文件

- 安裝：[ai-orchestrator/INSTALL.md](ai-orchestrator/INSTALL.md)
- Bootstrap：[ai-orchestrator/bootstrap.md](ai-orchestrator/bootstrap.md)
- Slack 治理：[slack-orchestrator/bootstrap-workspace.md](slack-orchestrator/bootstrap-workspace.md)

# Cursor Skills — AI Orchestrator

**帶到新專案（例如 l12cv）只需要複製本目錄。**

```bash
cp -R /path/to/ai-orchestrator-workflow-demo/.cursor/skills /path/to/your-project/.cursor/
```

不必複製 demo repo 的 `docs/`、`.cursor/rules/`、`.worktrees/`。  
在目標專案對 Cursor 說一句話，由 skill **bootstrap** 產生 rules 與 docs。

---

## 目錄

| Skill | 用途 |
|-------|------|
| [**ai-orchestrator/**](ai-orchestrator/) | 編排、handoff、GitHub、`docs/` + `rules` bootstrap |
| [**slack-orchestrator/**](slack-orchestrator/) | Slack 工作區治理（可選，建議 devstream-core 共用） |

---

## 三步驟

### 1. 複製 skills

```bash
DEMO=/path/to/ai-orchestrator-workflow-demo
TARGET=/path/to/l12cv   # 你的專案

mkdir -p "$TARGET/.cursor"
cp -R "$DEMO/.cursor/skills" "$TARGET/.cursor/"
```

### 2. Bootstrap（在目標專案開 Cursor）

```text
請依 ai-orchestrator/bootstrap.md 初始化本 repo：
產生 .cursor/rules/orchestrator.mdc 與 docs/。
Slack 產品頻道：#10-proj-l12cv（請用 MCP 查 channel_id）。
```

Agent 會讀 `ai-orchestrator/templates/`，寫入：

- `.cursor/rules/orchestrator.mdc`
- `docs/agent-roster.md`
- `docs/agent-handoff/PROTOCOL-v3.md`
- `docs/slack-handoff-template.md`
- `docs/progress.md`
- …（見 `ai-orchestrator/templates/manifest.json`）

### 3. 人工收尾（一次性）

- Slack `/invite` bots、`@Cursor settings`、Pin charter
- 第一次 `[feature]` → push task → `[handoff]` E2E

---

## 不要複製什麼

| 路徑 | 原因 |
|------|------|
| `docs/` | 由 bootstrap 在目標 repo 產生 |
| `.cursor/rules/` | 同上 |
| `.worktrees/` | demo 本機實驗，勿帶 |
| `.workflow-specs/` | 可選；bootstrap 可產生 |

---

## 本 demo repo 是什麼

`ai-orchestrator-workflow-demo` 的 **完整 AI demo**：skills 是**原始碼**，根目錄的 `docs/`、`rules/` 是**已跑過 E2E 的實例**（含歷史），給你對照用，不是給你 copy 的。

---

## 詳細文件

- 安裝：[ai-orchestrator/INSTALL.md](ai-orchestrator/INSTALL.md)
- Bootstrap：[ai-orchestrator/bootstrap.md](ai-orchestrator/bootstrap.md)
- Slack 治理：[slack-orchestrator/bootstrap-workspace.md](slack-orchestrator/bootstrap-workspace.md)

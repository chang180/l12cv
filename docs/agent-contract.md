# Agent 執行契約

本文件定義各角色在 workflow 中的權責與介面。主執行 orchestrator 為 **Cursor**。

## 角色定義

### ChatGPT（advisory）

- **可做**：發想、PRD、任務拆解、驗收標準、風險清單。
- **不可做**：直接寫入本 repo、直接操作 Slack/GitHub（除非人工轉貼）。
- **輸出格式**：建議以 Markdown 貼入 Slack thread，含標題、驗收條件、優先級。

### Cursor（orchestrator / executor）

- **可做**：
  - 讀寫 Slack thread（Slack MCP）
  - 建立/更新 GitHub issue、branch、PR（`gh`）
  - 更新 `docs/progress.md`
  - 依 [agent-roster.md](agent-roster.md) 發送 `@mention` 調用其他 bot
- **不可做**：
  - 假設可程式化部署 Slack Workflow Builder YAML
  - 假設可管理 Slack 側邊欄「頻道區段」
  - 未經 roster 登記即假設某 bot 已在頻道內

### Cursor Cloud（`@Cursor`）

- **觸發**：Slack `@Cursor [prompt]`，可帶 `branch=`、`autopr=`、repo 名稱。
- **可做**：遠端改 code、測試、開 PR、thread 內狀態通知。
- **與 IDE 分工**：IDE 負責編排與輕量任務；Cloud 負責重型實作（見 [architecture.md](architecture.md)）。

### Slack Workflow Builder

- **角色**：入口（shortcut、排程、表單），輸出結構化需求到頻道/thread。
- **executor**：`manual`（見 workflow spec 中 `setup_workflow_shortcut`）。

### 協作 Bot（Claude、Codex 等）

- **觸發**：Cursor 在 thread 內 `@mention`（見 roster）。
- **不可假設**：已安裝於 workspace 或已加入頻道——執行前查 roster 的 `channel_status`。

### GitHub App（`@github` / `<@U0B3VUN3QA1>`）

Slack 內**只有一個** GitHub bot；整合與 Copilot coding agent 共用此 mention（見 [slack-bot-mention-tests.md](slack-bot-mention-tests.md)）。

**整合模式**（slash）

- **觸發**：`/github subscribe|open|close|help`…
- **可做**：訂閱通知、開/關 issue、將 **既有 PR** 的 thread 脈絡同步進 PR。

**Copilot coding agent 模式**（on_demand）

- **觸發**：`<@U0B3VUN3QA1>` + 自然語言任務（例：`In owner/repo, …`）；**勿**用純文字 `@GitHub Copilot`（非 Slack mention）。
- **可做**：讀整串 thread、非同步寫 code、開 PR、issue 草稿（需 Copilot 訂閱與 repo write）。
- **分工**：Cursor 主控 repo 與 `docs/progress.md`；Copilot 負責委派出去的實作。
- **參考**：[Copilot cloud agent + Slack](https://docs.github.com/copilot/how-tos/use-copilot-agents/coding-agent/integrate-coding-agent-with-slack)
- **與 @Cursor**：同一任務擇一，避免重複開 PR。

## 步驟契約（對應 workflow spec）

| Step ID | Action | Executor | 輸入 | 輸出 |
|---------|--------|----------|------|------|
| `intake` | `slack.thread.read` | cursor | `channel_id`, `thread_ts` | 需求摘要 |
| `create_issue` | `github.issue.create` | cursor | `title`, `body` | issue URL |
| `create_branch` | `github.branch.create` | cursor | `branch_name` | branch ref |
| `notify_agents` | `slack.thread.message`（**notify_handoff**） | cursor | `mentions_from: agent-roster` | thread 訊息 |
| `update_progress` | `github.file.update` | cursor | `path: docs/progress.md` | commit |
| `setup_workflow_shortcut` | `slack.workflow.builder` | manual | runbook | Slack workflow ID |

## Branch + 任務文件 handoff（建議模式）

編排者（Cursor IDE）在 **push 前** 完成：

1. `git checkout -b feature/...`（或 `test/...`）
2. 撰寫 `docs/agent-handoff/PROTOCOL-v3.md` 與 `docs/agent-handoff/tasks/v2/TASK-<executor>.md`
3. `git push -u origin <branch>`
4. Slack **每 bot 一則**，含 `branch=`、任務檔 **GitHub blob URL**、交付路徑、**完成後 `@Cursor` + `handoff-complete` 格式**（見 [agent-handoff/COMPLETION-REPORT.md](agent-handoff/COMPLETION-REPORT.md)）

**v2 交付契約**（可複審）：

| Agent | 交付 |
|-------|------|
| Codex / @Cursor / @github | **PR** → 實驗 branch + `artifacts/v2/*-deliverable.md`（`status: ready_for_review`） |
| Claude | **Slack** 結構化審閱（`verdict:`），不要求 commit |

編排者複審：[agent-handoff/ORCHESTRATOR-REVIEW.md](agent-handoff/ORCHESTRATOR-REVIEW.md)。  
**Worktree** 僅編排者本機用；勿在 Slack 寫本機路徑。

## `slack.thread.message` 規則（notify_handoff）

**不是** sub-agent 自動執行佇列。Slack 只轉發 `app_mention` 給被 @ 的 App；各 bot 依**自家產品邏輯**回應。實測見 [slack-bot-mention-tests.md](slack-bot-mention-tests.md)。

1. 從 [agent-roster.md](agent-roster.md) 解析 `mention` 與 `role`。
2. 僅 mention `channel_status: joined` 的 bot；頻道內 **Claude/Codex/Copilot 須先完成 App 頻道綁 repo**（人工設定）。
3. 使用 [slack-bot-mention-tests.md](slack-bot-mention-tests.md) 的 **invoke 模板**，勿只寫「請 Claude 審閱」。
4. 預設 handoff：Claude、Codex；**GitHub Copilot 實作**僅 `on_demand`（`<@U0B3VUN3QA1>` + 任務）。
5. 訊息需含：issue 連結、branch 名、下一步；Codex 可能仍會附帶開 task。

範例：

```markdown
**編排更新**（Cursor）
- Issue: https://github.com/chang180/l12cv/issues/N
- Branch: `feature/bootstrap`
- 請 <@U0B404P284S> 審閱、<@U0B411CESCR> 實作
- （若需 GitHub Copilot 實作）<@U0B3VUN3QA1> In chang180/l12cv, …
```

## GitHub 寫入規則

- Issue title/body 來自 Slack thread 摘要。
- `docs/progress.md` 每次狀態變更必更新（時間、issue、branch、狀態）。
- PR 描述需連回 Slack thread permalink（若有）。

## 安全與隱私

- 私有頻道搜尋使用 `slack_search_public_and_private` 前需使用者同意。
- 不在 Slack 貼 secrets、token、`.env` 內容。

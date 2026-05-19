# Slack Handoff 模板（devstream-core）

可複製貼上至 `#20-agent-*` 或產品 thread。完整協定見 [agent-handoff/PROTOCOL-v3.md](agent-handoff/PROTOCOL-v3.md)。

**每則指派末尾必加**：

```text
Reply in Traditional Chinese (zh-TW).
```

---

## 1. 通用 handoff（編排 → executor）

```text
[handoff]

Task:
{{task}}

Repo:
{{repo}}

Branch:
{{branch}}

Acceptance:
{{acceptance}}

Reply in Traditional Chinese (zh-TW).
```

### 範例（Codex）

```text
[handoff]

Task:
實作登入頁 OAuth callback，任務文件見 GitHub TASK 連結。

Repo:
chang180/l12cv

Branch:
feature/oauth-callback

Acceptance:
- 單元測試通過
- 開 PR 至上述 branch，勿 merge main
- 完成後於本 thread 貼 handoff-complete

<@U0B411CESCR> Reply in Traditional Chinese (zh-TW).
```

---

## 2. 依 agent 頻道張貼

| 目標 | 頻道 | Mention |
|------|------|---------|
| Claude 審閱 | `#20-agent-claude` | `<@U0B404P284S>` |
| Cursor Cloud | `#21-agent-cursor` | `@Cursor` / `<@U09H5GMRSEQ>` |
| Codex 實作 | `#22-agent-codex` | `<@U0B411CESCR>` |
| GitHub Copilot | `#30-dev-github` 或 agent 頻道 | `<@U0B3VUN3QA1>` |

**規則**：同一任務勿同時 @ Cursor Cloud 與 @github 做重複實作。

---

## 3. Claude（review-only）

```text
[handoff]

Task:
{{task_summary}}

Repo:
{{repo}}

Branch:
{{branch}}

Acceptance:
僅於本 thread 回覆審閱（verdict、findings），勿開 PR、勿改 repo。

<@U0B404P284S> Reply in Traditional Chinese (zh-TW).
```

---

## 4. Cursor Cloud（execute）

```text
[handoff]

Task:
{{task}}

Repo:
{{repo}}

Branch:
{{branch}}

Acceptance:
{{acceptance}}

@Cursor Reply in Traditional Chinese (zh-TW).
```

---

## 4b. GitHub Copilot（execute，步驟化）

Copilot agent 需要**單一明確交付物**與**編號步驟**；勿只寫「implement TASK」。

```text
[handoff]

<@U0B3VUN3QA1> Reply in Traditional Chinese (zh-TW).

You are the GitHub Copilot coding agent. Do exactly:

1. Repo: {{repo}}, branch: {{branch}} (commit on this branch).
2. Create ONLY: {{single_file_path}} with content as in task file.
3. Push and open a PR with BASE branch {{branch}} (NOT main).
4. In this thread, post handoff-complete (see below).

Do NOT only reply "OK". Do NOT merge.

Task file: {{task_url}}
```

**完成回報（notes 必含一句）**：

```text
handoff-complete
task: {{task_id}}
status: ready_for_review
pr: {{pr_url}}
artifact: {{single_file_path}}
notes: 已完成，請通知 Cursor IDE 編排者複審。
```

---

## 5. Cursor Cloud（ack-only，測通路）

```text
[ask]

@Cursor ack-only: Reply in Traditional Chinese (zh-TW) with exactly one sentence: 「收到，編排者在線。」Do NOT open PR. Do NOT modify any file.
```

---

## 6. 完成回報（executor → 編排）

```text
[handoff-complete]

handoff-complete
task: {{task_id}}
status: ready_for_review | blocked | failed
pr: {{pr_url|n/a}}
artifact: {{path|n/a}}
notes: （繁中一句）
```

> **勿**將整段 `handoff-complete` **@Cursor** 當新工作單，否則 Cloud Agent 可能誤開 PR。

---

## 7. 產品頻道討論

### `#10-proj-l12cv`（本 repo）

```text
[feature]

（描述）

Repo: chang180/l12cv
Issue: {{issue_url|optional}}
```

本頻道含全 bot，可直接 `[handoff]` @ Codex／Claude／@Cursor；亦可分流至 `#20-agent-*`。

### 其他產品頻道（例 `#10-proj-other`）

同上方 `[feature]` 格式；`Repo` 改為該產品的 `owner/repo`。  
其他標籤：`[bug]`、`[release]`、`[ux]`、`[infra]`

---

## 8. 決策存檔（`#42-knowledge-decisions`）

```text
Decision:
（一句）

Why:
（背景）

Tradeoff:
（取捨與未採用方案）
```

---

## 變數對照

| 變數 | 說明 |
|------|------|
| `{{task}}` | 任務摘要或 GitHub task.md URL |
| `{{repo}}` | `owner/repo` |
| `{{branch}}` | PR base branch（須已 push 任務文件） |
| `{{acceptance}}` | 驗收條件條列 |
| `{{task_id}}` | 如 `T1`、`oauth-1` |

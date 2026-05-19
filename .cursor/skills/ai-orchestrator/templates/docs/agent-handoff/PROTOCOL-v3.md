# Handoff 協定 v3（定稿用於 Skill）

## 三種 Slack 訊息（勿混淆）

| 類型 | 誰發 | @ 誰 | 目的 |
|------|------|------|------|
| **A. 指派 execute** | IDE 編排 | executor（Codex / @github / @Cursor） | 請對方寫 code / 開 PR |
| **B. 完成回報** | executor | 可選 `<@U09H5GMRSEQ>` 喚醒通知 | 貼 `handoff-complete` 結構 |
| **C. Ping Cloud** | 人類或編排 | `@Cursor` + `ack-only` | 只測通路或確認收到，**禁止**當成 B |

> **錯誤實例**：把 `handoff-complete task:T0` **直接 @Cursor** → Cloud Agent 當成編排任務，開 PR、日文回覆。

## 編排者雙軌

| 軌道 | 觸發 | 工具 |
|------|------|------|
| **IDE 編排**（主） | 人開 Cursor / Slack 通知 | Slack MCP + `gh` + 本 repo |
| **Cloud @Cursor** | Slack `@Cursor` + 任務 | 遠端 VM、可開 PR；**非** IDE 常駐 |

## 語言（必寫）

所有 Slack 指派加一行：

```text
Reply in Traditional Chinese (zh-TW).
```

## A. 指派模板

### Codex / @github（execute → PR）

**@github** 請用**步驟化**指派（見 `docs/slack-handoff-template.md` §4b），完成時 `notes` 含：`已完成，請通知 Cursor IDE 編排者複審。`

```text
<@EXECUTOR_ID> Reply in Traditional Chinese (zh-TW).

You are the coding agent. Do exactly:
1. Repo: {{GITHUB_FULL_REPO}}, branch: test/agent-handoff-v2
2. Create ONLY: docs/agent-handoff/artifacts/v2/xxx-deliverable.md (see task file)
3. Push; open PR with BASE branch test/agent-handoff-v2 (NOT main)
4. Post handoff-complete in this thread; notes must say: 已完成，請通知 Cursor IDE 編排者複審。

Task: https://github.com/.../blob/test/agent-handoff-v2/docs/agent-handoff/tasks/v2/TASK-xxx.md
```

### @Cursor Cloud（execute）

同上，但 executor 為 `<@U09H5GMRSEQ>`；**勿**與 T2 自己回報時再開新任務。

### Claude（review-only）

```text
<@U0B404P284S> Reply in Traditional Chinese (zh-TW).
Post review in this thread only (verdict, findings). No PR, no code session.
Then: handoff-complete task:T4 ...
```

### @Cursor（ack-only，Ping）

```text
@Cursor ack-only: Reply in Traditional Chinese (zh-TW) with exactly one sentence: 「收到，編排者在線。」Do NOT open PR. Do NOT modify any file.
```

## B. 完成回報（executor → 編排）

```text
handoff-complete
task: T1
status: ready_for_review | blocked | failed
pr: <url|n/a>
artifact: <path|n/a>
notes: （繁中一句）
```

- **建議**末尾加 `<@U09H5GMRSEQ>` 以喚醒 Slack 通知（會觸發 Cloud 時需用 **ack-only** 約束，見實測）。
- **IDE 編排**應定期 `slack_read_thread` 或等使用者轉述。

## IDE 編排者收到後

1. `slack_read_thread` 找 `handoff-complete`
2. `gh pr list` / `gh pr diff`
3. [ORCHESTRATOR-REVIEW.md](ORCHESTRATOR-REVIEW.md)
4. 更新 `docs/progress.md`、issue 留言

## v3 驗證清單

- [ ] Test A：`ack-only` → 繁中一句、無 PR（5 分鐘內）
- [ ] Test B：Codex execute 模板 → PR 或 blocked + handoff-complete
- [ ] Test C：Claude review → 繁中 + handoff-complete 行
- [ ] 結果寫入 [TEST-RESULTS.md](TEST-RESULTS.md)

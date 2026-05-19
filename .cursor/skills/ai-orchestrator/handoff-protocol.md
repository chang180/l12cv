# Handoff 協定（Skill 精簡版）

> 完整版：bootstrap 後的 `docs/agent-handoff/PROTOCOL-v3.md`（模板在 `templates/docs/agent-handoff/PROTOCOL-v3.md`）

## 三種訊息（勿混淆）

| 類型 | 範例 | 說明 |
|------|------|------|
| **指派 execute** | `@Codex` + repo/branch/task URL | 請 executor 開 PR |
| **完成回報** | `handoff-complete` + task/status/pr | executor 完成後貼；**不要**把這行當成給 @Cursor 的工作指令 |
| **Ping Cloud** | `@Cursor ack-only: 繁中一句，勿開 PR` | 只測通路 |

## 必寫

- `Reply in Traditional Chinese (zh-TW).`
- PR **base branch** 明確（非 main，除非刻意）
- 先 **push 任務文件** 再發 Slack（否則 Claude 等會 `blocked`）

## @Cursor 注意

- `@Cursor` = **Cloud Agent**（可能改檔、開 PR、預設日文/英文）
- **IDE 編排** = 本機 Cursor + Slack MCP + `gh`（不會自動聽 Slack）
- 完成回報若要通知：executor 貼 `handoff-complete`；人看到後開 IDE，或 Codex 末尾可選 @Cursor（慎用）

## IDE 編排者循環

1. 建 branch + `docs/agent-handoff/tasks/...` → **push**
2. Slack 每 executor 一則（模板見 PROTOCOL-v3）
3. `slack_read_thread` 搜 `handoff-complete`
4. `gh pr diff` → 複審 → `docs/progress.md` + issue 留言

## GitHub bot

- 僅 **`@github`** / `<@U0B3VUN3QA1>`，無獨立 Copilot bot
- Copilot 實作 = 自然語言任務 + 開 PR

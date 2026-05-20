# Slack 進度鏡像與可讀性（v4）

> v4 **不**用 Slack 派工，但**仍要**讓產品頻道成為「人類與 Slack 內 AI 看得見的進度看板」。  
> GitHub `docs/progress.md` 是 SoT；Slack `[status]` 是**鏡像 + 可搜尋摘要**。

## 雙層進度

| 層 | 位置 | 誰寫 | 誰讀 |
|----|------|------|------|
| **SoT** | `docs/progress.md`（GitHub） | Cursor IDE | 本機 AI、`gh`、所有人 |
| **鏡像** | `#10-proj-*` 的 `[status]` | Cursor IDE（Slack MCP） | 人類、Slack 內 bot（被 @ 或搜尋時） |

**順序**：先 `git push` progress → 再發 `[status]`（內容與 SoT 一致）。

## 編排者：發送（Slack MCP）

```text
slack_send_message
  channel_id: <產品頻道 ID，見 agent-roster.md>
  message: （見 slack-handoff-template.md §1 [status]）
```

**禁止**：在 `[status]` 內 @Cursor / @codex / @claude / @github（避免誤觸 execute）。

## 編排者：讀回（驗證鏡像成功）

```text
1. slack_read_channel(channel_id, limit=10)
   → 確認最新一則含 [status] 與 SoT URL

2. slack_search_public(
     query="[status] in:#<頻道名>",
     sort="timestamp", sort_dir="desc", limit=5
   )
   → 跨日搜尋進度快照
```

## Slack 內 AI 如何「讀到」進度

| AI | 典型讀法 | v4 建議 |
|----|----------|---------|
| **Cursor IDE** | Slack MCP `slack_read_channel` / `slack_search_public` | 編排者複審、發 [status] 前後自測 |
| **Claude（Slack）** | 被 @ 時讀**該 thread** 或頻道可見歷史 | 進度用 **Pin 的 progress thread**；唯讀用 `[read-only]`（見下） |
| **@Cursor Cloud** | @ 時可能開 PR | **勿**在 [status] @；唯讀亦避免 @Cursor |
| **Codex / @github** | @ 時可能開 task/PR | v4 預設不用 |

### 可選：Pin「進度串」

1. 在產品頻道發一則說明 → **Pin**（`thread_ts` 記入 `docs/progress.md` 的 `Slack progress thread`）。
2. 之後每次 `[status]` 用 **`thread_ts` 回覆**同一串 → Slack 內 AI 被 @ 時上下文集中。
3. 避免在頻道 top-level 開新 thread 導致 Claude 綁錯舊 session。

### 可選：唯讀喚醒（非派工）

僅當需要 **Slack 內 Claude** 用自然語言摘要進度時，在 **progress thread** 發（勿在 [status] 主文 @）：

```text
[read-only]

<@U0B404P284S> Reply in Traditional Chinese (zh-TW).
Read the latest [status] above and the SoT link. Summarize Phase + active tasks only.
Do NOT change code. Do NOT open PR. Do NOT start new work.
```

這是 **ask / 摘要**，不是 `[handoff]` execute。若 bot 仍誤動作，改為本機 Claude Code 讀 GitHub SoT。

## E2E 測試清單（每專案 bootstrap 後）

- [ ] `slack_send_message` 發 `[status]`（無 @ bot）
- [ ] `slack_read_channel` 讀到同一則
- [ ] `slack_search_public`：`[status] in:#<channel>` 有結果
- [ ] SoT URL 可開且與 Slack 摘要一致
- [ ] （可選）progress thread + `[read-only]` Claude 回覆摘要

## 相關

- 模板：`templates/docs/slack-handoff-template.md` §1、§6
- 能力邊界：`templates/docs/slack-capability-matrix.md`
- 主協定：`templates/docs/WORKFLOW-v4-LOCAL-FIRST.md.tpl`

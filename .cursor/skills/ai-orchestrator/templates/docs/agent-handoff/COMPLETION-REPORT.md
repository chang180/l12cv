# 完成回報協定（給所有 executor）

**不要**讓編排者空等 30–60 分鐘。交付完成後 **立刻** 在 **同一 Slack handoff thread** 回報。

## 編排者是誰？

| 名稱 | Slack | 用途 |
|------|-------|------|
| **Cursor Cloud** | `<@U09H5GMRSEQ>`（@Cursor） | **會啟動 Cloud Agent**（改 code、開 PR），不是只回一句 |
| **Cursor IDE 編排** | 本機對話 | 人看到 Slack 後開 IDE，或用 MCP 讀 thread |

> **實測 T0（2026-05-18）**：只貼 `handoff-complete task:T0` → Cloud Agent 以**日文**回 thread，並開 **PR #8** 改 `docs/progress.md`（非預期的 ping 測試）。

### 避免誤觸發 Cloud Agent

| 意圖 | 作法 |
|------|------|
| **只測 Slack 通路** | 不要 @Cursor；由人類貼格式即可 |
| **要 Cloud 只回一句** | `@Cursor ack-only: 用繁體中文回覆「收到 T0」即可。不要開 PR、不要改檔。` |
| **要 Cloud 幹活** | 明寫 `branch=`、任務檔 URL、**Reply in Traditional Chinese (zh-TW)** |

### 語言

Cloud Agent **未指定時可能用日文或英文**。Slack 指派請加：**`Reply in Traditional Chinese (zh-TW).`**

## 固定格式（複製貼上）

```text
<@U09H5GMRSEQ> handoff-complete

task: T1
status: ready_for_review
pr: https://github.com/{{GITHUB_FULL_REPO}}/pull/NNN
artifact: docs/agent-handoff/artifacts/v2/codex-deliverable.md
notes: （可選，一句繁中）
```

| 欄位 | 必填 | 說明 |
|------|------|------|
| `task` | ✅ | T1 / T2 / T3 / T4 |
| `status` | ✅ | `ready_for_review` \| `blocked` \| `failed` |
| `pr` | T1–T3 | PR URL；無 PR 填 `n/a` |
| `artifact` | T1–T3 | 交付檔路徑 |
| `notes` | 選填 | 阻礙或偏離任務說明 |

## 依角色

| Agent | 完成時 |
|-------|--------|
| Codex / @github | 開好 PR 後，**同 thread** 貼 `handoff-complete`；`notes` **必含**：`已完成，請通知 Cursor IDE 編排者複審。` |
| @Cursor Cloud（T2） | PR 就緒後貼 `handoff-complete`（**不要**再開新任務；僅回報） |
| Claude（review-only） | 先貼審閱 + `handoff-complete`（**勿**在同行 @Cursor 當 execute） |
| Claude 喚醒通知（可選） | 完成回報**之後**另起一行 `ack-only` ping（見下） |

### Claude 完成時通知編排（可選）

Slack **沒有**「Claude 完成自動 @Cursor」的全域開關；請寫進 **任務文件** 或 Claude 頻道／專案指示。

**安全做法**（喚醒通知，不觸發開 PR）：

```text
handoff-complete
task: T1
status: ready_for_review
pr: n/a
artifact: n/a
notes: verdict=pass

<@U09H5GMRSEQ> ack-only: 請以繁中回覆一句「T1 已完成，編排者可讀 thread。」Do NOT open PR. Do NOT modify any file.
```

**避免**：`<@U09H5GMRSEQ> handoff-complete task:T0` 單行 @Cursor → Cloud 會當成新任務開 PR（T0 實測）。

**IDE 編排（本機 Cursor）**：不會自動訂閱 Slack；仍需你開 IDE、或定期 `slack_read_thread`，或由執行者 `notes` 明示「請通知 Cursor IDE」、你再轉述。

### @github Copilot 指派要點

- 用 **編號步驟** + **單一檔案路徑**（見 `slack-handoff-template.md` §4b）
- 明寫 `branch=` 與 **PR base 不是 main**
- 勿只寫 “read TASK and implement” — 易只回覆不開 PR

## 編排者收到後（IDE Cursor）

1. 讀 thread 的 `handoff-complete` 訊息  
2. `gh pr view` / `gh pr diff`（若有 `pr:`）  
3. 執行 [ORCHESTRATOR-REVIEW.md](ORCHESTRATOR-REVIEW.md)  
4. 回 thread 或 issue 寫複審結論  

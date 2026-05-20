# Slack 頻道分類（{{SLACK_WORKSPACE}}）

**目的**：AI Engineering OS — 進度看板、audit log、決策存檔（v4 local-first）。

## 分層模型

| Layer | 前綴 | 角色（v4） |
|-------|------|------------|
| 0 | `00-` | 全 workspace 通用 |
| 1 | `10-proj-*` / `11-proj-*` | 產品線 — **進度看板** `[status]` |
| 2 | `20-agent-*` | Agent 專線 — **預設閒置** |
| 3 | `30-dev-*` / `31-` / `32-` | 工程、發版、缺陷 |
| 4 | `40-knowledge-*` / `42-*` | 知識與決策 |
| 9 | `99-` | 孵化 |

## 頻道登錄（{{DATE}} 稽核）

{{CHANNEL_ROWS}}

## 訊息標籤

### 產品頻道 `10-proj-*` / `11-proj-*`

`[feature]` `[bug]` `[release]` `[ux]` `[infra]` · **`[status]`** **`[decision]`**

（`[handoff]` 僅 legacy 例外，見 `docs/WORKFLOW-v4.md`）

### Agent 頻道 `20-agent-*`（v4 預設閒置）

歷史：`[ask]` `[handoff]` `[handoff-complete]` — 非新專案預設

### `#42-knowledge-decisions`

```text
Decision:
Why:
Tradeoff:
```

## 更新紀錄

| 日期 | 變更 |
|------|------|
| {{DATE}} | slack-orchestrator bootstrap · v4 taxonomy |

# Slack 頻道分類（{{SLACK_WORKSPACE}}）

**目的**：AI Engineering OS — command center、audit log、handoff bus、decision archive。

## 分層模型

| Layer | 前綴 | 角色 |
|-------|------|------|
| 0 | `00-` | 全 workspace 通用 |
| 1 | `10-proj-*` / `11-proj-*` | 產品線 |
| 2 | `20-agent-*` | Agent handoff bus |
| 3 | `30-dev-*` / `31-` / `32-` | 工程、發版、缺陷 |
| 4 | `40-knowledge-*` / `42-*` | 知識與決策 |
| 9 | `99-` | 孵化 |

## 頻道登錄（{{DATE}} 稽核）

{{CHANNEL_ROWS}}

## 訊息標籤

### 產品頻道 `10-proj-*` / `11-proj-*`

`[feature]` `[bug]` `[release]` `[ux]` `[infra]`

### Agent 頻道 `20-agent-*`

`[ask]` `[handoff]` `[handoff-complete]`

### `#42-knowledge-decisions`

```text
Decision:
Why:
Tradeoff:
```

## 更新紀錄

| 日期 | 變更 |
|------|------|
| {{DATE}} | slack-orchestrator bootstrap |

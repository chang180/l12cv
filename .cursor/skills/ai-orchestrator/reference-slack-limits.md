# Slack / 整合能力邊界（精簡）

## 編排雙軌

| | Cursor IDE | @Cursor Slack |
|--|------------|---------------|
| 觸發 | 本機對話 / 人轉述 | Slack @mention |
| 行為 | MCP + `gh` | Cloud Agent，常開 PR |
| 語言 | 跟隨對話 | **需指定 zh-TW**，否則可能日文 |

## Handoff 三類訊息

| 類型 | 誤用後果 |
|------|----------|
| execute 指派 | — |
| handoff-complete 回報 | 若 **@Cursor** → 當成編排任務（T0/PR#8） |
| ack-only ping | 唯一安全的「只回一句」@Cursor 方式 |

## MCP 常見誤判

| 以為可以 | 實際 |
|----------|------|
| handoff-complete @Cursor = 通知編排 | 觸發 Cloud 幹活 |
| 有獨立 @GitHub Copilot bot | 僅 `@github` |
| 先 Slack 後 push 任務檔 | Claude 等回 blocked |
| Contributors 有 Claude 帳號 | 僅你或 copilot-swe-agent 等 |

## GitHub App（同一 @github）

| 模式 | 觸發 |
|------|------|
| 整合 | `/github subscribe` |
| Copilot PR | `<@U0B3VUN3QA1>` + 任務 + branch |

## 官方文件

- https://cursor.com/docs/integrations/slack
- https://docs.github.com/copilot/how-tos/use-copilot-agents/coding-agent/integrate-coding-agent-with-slack

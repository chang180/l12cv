# Slack 能力矩陣

本文件記錄 **Cursor IDE + Slack MCP**、**@Cursor Cloud Agent** 與 **僅能人工** 的能力邊界，避免誤以為 API 可完成 Slack UI 上的所有操作。

**適用 workspace**：`{{SLACK_WORKSPACE}}`（2026-05-18 治理稽核）

## 總覽

| 功能 | Slack MCP (IDE) | @Cursor Cloud | 僅能人工 |
|------|-----------------|---------------|----------|
| 讀取公開頻道訊息 | Yes | 觸發時讀 thread | — |
| 讀取 thread 回覆 | Yes | Yes | — |
| 發送訊息 / 回 thread | Yes | Yes（狀態、PR） | — |
| 排程訊息 | Yes (`slack_schedule_message`) | — | — |
| Emoji 反應 | Yes | Yes | — |
| 搜尋公開訊息 | Yes | 限 thread 脈絡 | — |
| 搜尋私有頻道 | 需同意 + `slack_search_public_and_private` | — | — |
| Canvas 讀寫（section） | Yes（需 `section_id`） | — | — |
| 建立頻道 + 建立時邀請成員 | Yes (`slack_create_conversation`) | join public | — |
| **重新命名頻道** | **No** | **No** | **Yes** |
| **歸檔頻道** | **No** | **No** | **Yes** |
| **邀請 bot 進既有頻道** | **No** | 需 `/invite` | **Yes** |
| **列出 workspace 已安裝 App** | **No** | **No** | **Yes**（管理後台） |
| **側邊欄頻道區段** | **No** | **No** | **Yes** |
| **從 repo YAML 部署 Workflow Builder** | **No** | **No** | **Yes** / Deno SDK+CLI |
| Workflow Builder shortcut | — | — | Yes |
| 觸發 Cloud Agent | — | `@Cursor` | 安裝整合 |
| 觸發 GitHub Copilot agent | — | — | `<@U0B3VUN3QA1>` + 自然語言任務（與 `@github` 同一 App） |
| `/github open` 開 issue | 否 | 否 | `@github` slash |

## Slack MCP 可用工具（本 workspace）

| 工具 | 用途 |
|------|------|
| `slack_read_channel` | 讀頻道歷史 |
| `slack_read_thread` | 讀 thread |
| `slack_send_message` | 發訊息 |
| `slack_send_message_draft` | 草稿 |
| `slack_schedule_message` | 排程 |
| `slack_search_channels` | 搜尋頻道（**非**完整 list；需多 query） |
| `slack_search_public` | 搜尋公開訊息 |
| `slack_search_users` | 搜尋使用者（bot 不一定可搜到） |
| `slack_list_channel_members` | 頻道成員（**必須** `include_bots: true`） |
| `slack_add_reaction` | 反應 |
| `slack_read_canvas` / `slack_update_canvas` | Canvas（非頻道區段） |
| `slack_create_conversation` | 建立頻道 |

## {{SLACK_WORKSPACE}} 稽核限制（2026-05-18）

| 限制 | 影響 |
|------|------|
| `slack_search_channels` 無「列出全部」 | 需以 `00-`、`10-proj`、`20-agent` 等分批搜尋，可能漏掉未命名頻道 |
| 搜尋結果**不含** member count | 以 `slack_list_channel_members` 逐頻道補齊 |
| MCP 無法 `/invite` | Bot 部署需人工；見 [slack-channel-taxonomy.md](slack-channel-taxonomy.md) |
| 無法 rename `#42-knowledge-desicions` | 需 Slack UI 修正拼字 |

## 常見混淆

### 「頻道區段」vs「Canvas section」

- **頻道區段**：Slack 側邊欄整理用，**無公開 API** → `manual_only`。
- **Canvas section**：Canvas 文件內區塊，MCP 可透過 `slack_read_canvas` 取得 `section_id` 後 `slack_update_canvas`。

### Workflow spec YAML vs Slack Workflow

- Repo 內 [slack-demo-request.yml](../.workflow-specs/slack-demo-request.yml) 是 **contract**，不是 Slack 可 import 的定義檔。
- 建立流程請用 [Workflow Builder](https://docs.slack.dev/workflows/workflow-builder) 或 [Deno Slack SDK](https://docs.slack.dev/workflows)。

## 清查已安裝 App 的替代做法

Slack MCP **沒有** `admin.apps.list`：

1. `slack_list_channel_members` + `include_bots: true`（僅已在頻道內的 bot）
2. `slack_search_public` + `include_bots: true`（從系統訊息反查，如 Claude 加入頻道）
3. Workspace **設定 → 管理應用程式** 手動補登至 [agent-roster.md](agent-roster.md)

## 相關文件

- [slack-channel-taxonomy.md](slack-channel-taxonomy.md) — 頻道分層與 ID
- [slack-handoff-template.md](slack-handoff-template.md) — 可複製模板
- [.cursor/skills/slack-orchestrator/SKILL.md](../.cursor/skills/slack-orchestrator/SKILL.md) — Cursor skill

## 參考連結

- [Cursor Slack 整合](https://cursor.com/docs/integrations/slack)
- [Cursor Cloud Agents](https://cursor.com/docs/cloud-agent)
- [Cursor MCP](https://cursor.com/docs/mcp)
- [Slack Workflows](https://docs.slack.dev/workflows)
- [Slack Workflow Builder](https://docs.slack.dev/workflows/workflow-builder)

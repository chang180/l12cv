# Bootstrap：Slack 工作區治理文件

> 與 **ai-orchestrator** `bootstrap.md` 搭配。  
> 僅在需要 `docs/slack-channel-taxonomy.md` 時執行（新 workspace 或首次治理）。

## 產出

| 輸出 | 模板 |
|------|------|
| `docs/slack-channel-taxonomy.md` | `templates/slack-channel-taxonomy.md.tpl` |

## 執行

1. `slack_search_channels` 分批搜尋（`00-`、`10-proj`、`20-agent`…）
2. `slack_list_channel_members` + `include_bots: true`
3. 填入模板中的頻道表（`{{CHANNEL_ROWS}}` 由 Agent 產生 markdown 列）
4. 可選：`slack_send_message` 發各頻道 charter（見 `templates/charter-product.md.tpl`）

## 變數

- `{{SLACK_WORKSPACE}}` — 例 `devstream-core`
- `{{DATE}}`
- `{{CHANNEL_ROWS}}` — 稽核結果表格列

## 注意

- 本 skill **不**取代 `docs/agent-roster.md`（專案級，由 ai-orchestrator bootstrap）
- taxonomy 為 **workspace 級**；各產品 repo 的 `10-proj-*` / `11-proj-*` 在 roster 註冊即可

# Templates

由 [bootstrap.md](../bootstrap.md) 讀取 [manifest.json](manifest.json)，替換 `{{...}}` 後寫入目標 repo。

| 佔位符 | 說明 |
|--------|------|
| `{{GITHUB_OWNER}}` | GitHub owner |
| `{{GITHUB_REPO}}` | repo 名 |
| `{{GITHUB_FULL_REPO}}` | `owner/repo` |
| `{{SLACK_WORKSPACE}}` | Slack workspace 名 |
| `{{SLACK_PROJECT_CHANNEL}}` | 產品頻道名（無 #） |
| `{{SLACK_PROJECT_CHANNEL_ID}}` | `C…` |
| `{{PRODUCT_NAME}}` | 產品顯示名 |
| `{{DATE}}` | ISO 日期 |

`.tpl` 結尾 = 需替換；無 `.tpl` = 內容已泛化或僅少量佔位符。

# Bootstrap：從 Skill 重建 Rules + Docs

> **其他專案只需複製 `.cursor/skills/`**（`ai-orchestrator` + 建議 `slack-orchestrator`）。  
> 本檔為 Agent **必讀**：在目標 repo 內產生 `.cursor/rules/` 與 `docs/`，無需從 demo repo 複製文件。

## 何時執行

- 新專案第一次啟用 AI orchestrator
- 使用者說：「bootstrap orchestrator」「初始化編排文件」
- `docs/agent-roster.md` 不存在或過期

## 輸入（先收集，可 `gh` + Slack MCP 自動填）

| 變數 | 來源 | 範例 |
|------|------|------|
| `GITHUB_OWNER` | `gh repo view --json owner` | `chang180` |
| `GITHUB_REPO` | `gh repo view --json name` | `l12cv` |
| `SLACK_WORKSPACE` | 使用者或 MCP | `devstream-core` |
| `SLACK_PROJECT_CHANNEL` | `slack_search_channels` | `10-proj-l12cv` |
| `SLACK_PROJECT_CHANNEL_ID` | 搜尋結果 | `C0B47UBS2HH` |
| `PRODUCT_NAME` | 使用者 | `l12cv` |

**devstream-core 共用 bot user_id**（workspace 級，見 `templates/docs/agent-roster.md.tpl` 內建表）：除非新 workspace，否則保留模板預設。

## 執行順序

```
1. 確認 .cursor/skills/ai-orchestrator/ 存在（已複製 skill）
2. 讀 templates/manifest.json — 列出要寫入的檔案
3. 以變數替換 {{...}}，寫入目標路徑
4. slack_list_channel_members(project_channel, include_bots=true) → 更新 roster
5. 若 devstream-core：可選執行 slack-orchestrator/bootstrap-workspace.md
6. 告知使用者：/invite bots、@Cursor settings、發 charter（slack-orchestrator）
7. 建議第一次 E2E：見 SKILL.md 標準執行順序
```

## 產出清單（必建）

| 輸出路徑 | 模板 |
|----------|------|
| `.cursor/rules/orchestrator.mdc` | `templates/orchestrator.mdc.tpl` |
| `docs/progress.md` | `templates/docs/progress.md.tpl` |
| `docs/agent-roster.md` | `templates/docs/agent-roster.md.tpl` |
| `docs/agent-contract.md` | `templates/docs/agent-contract.md` |
| `docs/slack-handoff-template.md` | `templates/docs/slack-handoff-template.md` |
| `docs/agent-handoff/PROTOCOL-v3.md` | `templates/docs/agent-handoff/PROTOCOL-v3.md` |
| `docs/agent-handoff/COMPLETION-REPORT.md` | `templates/docs/agent-handoff/COMPLETION-REPORT.md` |
| `docs/agent-handoff/ORCHESTRATOR-REVIEW.md` | `templates/docs/agent-handoff/ORCHESTRATOR-REVIEW.md.tpl` |
| `docs/agent-handoff/README.md` | `templates/docs/agent-handoff/README.md.tpl` |
| `docs/slack-capability-matrix.md` | `templates/docs/slack-capability-matrix.md` |

## 可選產出

| 輸出路徑 | 模板 | 時機 |
|----------|------|------|
| `.workflow-specs/{{repo}}-request.yml` | `templates/workflow-specs/project-request.yml.tpl` | 要做 Workflow Builder 契約 |
| `.workflow-specs/{{repo}}-request.md` | `templates/workflow-specs/project-request.md.tpl` | 同上 |
| `docs/slack-channel-taxonomy.md` | 見 **slack-orchestrator** skill | 新 workspace 或首次治理 |

## 替換規則

- 將所有 `{{GITHUB_OWNER}}`、`{{GITHUB_REPO}}`、`{{GITHUB_FULL_REPO}}`（`owner/repo`）
- `{{SLACK_PROJECT_CHANNEL}}`、`{{SLACK_PROJECT_CHANNEL_ID}}`
- `{{PRODUCT_NAME}}`、`{{DATE}}`（ISO 日期）

**勿**在模板內留未替換的 `{{...}}`。

## 與本 demo repo 的關係

`ai-orchestrator-workflow-demo` 的 `docs/`、`.cursor/rules/` 是 **living demo 實例**（含 E2E 歷史）。  
其他產品 repo **不必**複製 demo 的 `docs/`，用本 bootstrap 即可。

## 驗收

- [ ] `.cursor/rules/orchestrator.mdc` 存在且 `globs` 含 `**/*`
- [ ] `docs/agent-roster.md` 含正確 `channel_id`
- [ ] `docs/agent-handoff/PROTOCOL-v3.md` 存在
- [ ] `gh repo view` 與 roster 的 repo 一致

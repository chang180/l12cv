# Bootstrap：從 Skill 重建 Rules + Docs（v4）

> **其他專案只需複製 `.cursor/skills/`**。  
> 在目標 repo 產生 `.cursor/rules/` 與 `docs/`，**勿**從 demo 複製 `docs/`。

## 何時執行

- 新專案第一次啟用 AI orchestrator
- 使用者說：「bootstrap orchestrator v4」「初始化編排文件」
- 升級 v3 → v4（見 [MIGRATION-v3-to-v4.md](MIGRATION-v3-to-v4.md)）

## 輸入（先收集）

| 變數 | 來源 | 範例 |
|------|------|------|
| `GITHUB_OWNER` | `gh repo view --json owner` | `chang180` |
| `GITHUB_REPO` | `gh repo view --json name` | `l12cv` |
| `GITHUB_FULL_REPO` | `owner/repo` | `chang180/l12cv` |
| `SLACK_WORKSPACE` | 使用者或 MCP | `devstream-core` |
| `SLACK_PROJECT_CHANNEL` | `slack_search_channels` | `10-proj-l12cv` |
| `SLACK_PROJECT_CHANNEL_ID` | 搜尋結果 | `C0B47UBS2HH` |
| `PRODUCT_NAME` | 使用者 | `l12cv` |
| `DATE` | 今天 ISO | `2026-05-20` |

**禁止**在模板留未替換的 `{{...}}`。勿寫死其他專案的 repo / channel ID。

## 執行順序

```
1. 確認 .cursor/skills/ai-orchestrator/ 存在
2. 讀 templates/manifest.json（version 2.0.0, workflow v4-local-first）
3. 替換 {{...}}，寫入 required 輸出
4. 建立 docs/tasks/、docs/decisions/（若尚無）
5. slack_list_channel_members(project_channel, include_bots=true) → 更新 roster
6. 可選：manifest optional（PROTOCOL-v3、workflow-specs）
7. 可選：slack-orchestrator/bootstrap-workspace.md
8. 告知：Pin v4 charter、首則 [status]、勿預設 @ bot
9. 驗收：PORTABILITY.md checklist
```

## 產出清單（required · v4）

| 輸出 | 模板 |
|------|------|
| `.cursor/rules/orchestrator.mdc` | `orchestrator.mdc.tpl` |
| `docs/WORKFLOW-v4.md` | `docs/WORKFLOW-v4-LOCAL-FIRST.md.tpl` |
| `docs/progress.md` | `docs/progress.md.tpl` |
| `docs/agent-roster.md` | `docs/agent-roster.md.tpl` |
| `docs/agent-contract.md` | `docs/agent-contract.md` |
| `docs/slack-handoff-template.md` | `docs/slack-handoff-template.md` |
| `docs/tasks/_TEMPLATE.md` | `docs/tasks/_TEMPLATE.md.tpl` |
| `docs/slack-capability-matrix.md` | `docs/slack-capability-matrix.md` |

## 可選產出

| 輸出 | 模板 |
|------|------|
| `docs/decisions/_TEMPLATE.md` | `docs/decisions/_TEMPLATE.md.tpl` |
| `docs/agent-handoff/PROTOCOL-v3.md` | legacy |
| `.workflow-specs/{{repo}}-request.*` | workflow-specs |
| `docs/slack-channel-taxonomy.md` | slack-orchestrator skill |

## 與 demo repo

`ai-orchestrator-workflow-demo` 根目錄 `docs/` 為 **living demo**，非必複製。  
其他產品 repo 用本 bootstrap 即可。

## 驗收

見 [PORTABILITY.md](PORTABILITY.md)。

# 可攜性驗收（v4）

確認 skills 可在**任意新 repo** 落地，且**不依賴** demo repo 的 `docs/`。

## 前置

- [ ] 目標機器已安裝 `gh`、Slack MCP（編排用）
- [ ] 已 `cp -R` 整個 `.cursor/skills/` 至目標 repo
- [ ] **未**複製 demo 的 `docs/` 或 `.cursor/rules/`

## Bootstrap

- [ ] 執行 [bootstrap.md](bootstrap.md)（帶正確 `SLACK_PROJECT_CHANNEL_ID`）
- [ ] `docs/WORKFLOW-v4.md` 存在
- [ ] `docs/progress.md` 存在
- [ ] `.cursor/rules/orchestrator.mdc` 存在
- [ ] `docs/tasks/_TEMPLATE.md` 存在
- [ ] `docs/agent-roster.md` 的 channel_id = **該專案**頻道（非其他專案 ID）
- [ ] 全文**無**未替換的 `{{GITHUB_*}}`、`{{SLACK_*}}`

## 模板衛生（維護 skills 時）

```bash
# 在 skills 目錄執行：不應出現 demo 專用 repo/channel（模板變數除外）
rg 'ai-orchestrator-workflow-demo|C0B40L36REE' .cursor/skills/ai-orchestrator/templates \
  --glob '*.tpl' --glob '*.md' || true
# 僅允許出現在 MIGRATION/PORTABILITY 說明文字，非 .tpl 內容
```

## 執行循環

- [ ] 新增 `docs/tasks/T-001-*.md`
- [ ] 本機完成最小變更（或僅文件）
- [ ] 更新 `docs/progress.md` → commit → push
- [ ] Slack `[status]` **無** `@` bot
- [ ] 24h 內產品頻道無 Cloud Agent 誤開 PR

## 可選

- [ ] `docs/decisions/_TEMPLATE.md`（manifest optional）
- [ ] `docs/agent-handoff/PROTOCOL-v3.md`（legacy optional）

## 失敗時

| 症狀 | 檢查 |
|------|------|
| `{{...}}` 殘留 | bootstrap 替換步驟 |
| 錯誤 channel_id | `slack_search_channels` 重查 |
| Cloud 誤開 PR | `[status]` 是否含 @；頻道是否仍 @Cursor |

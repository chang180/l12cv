# v3 → v4 遷移

已用舊版 ai-orchestrator bootstrap、且含 `PROTOCOL-v3` / `[handoff]` 流程的專案，可按下述升級。**不必**複製 demo repo 的 `docs/`。

## 1. 更新 skills

```bash
cp -R /path/to/ai-orchestrator-workflow-demo/.cursor/skills \
  /path/to/your-project/.cursor/
```

## 2. 新增 v4 檔案（可手動或重新 bootstrap）

| 檔案 | 動作 |
|------|------|
| `docs/WORKFLOW-v4.md` | 自模板產生或 merge |
| `docs/tasks/_TEMPLATE.md` | 新增 |
| `docs/decisions/_TEMPLATE.md` | 可選 |
| `.cursor/rules/orchestrator.mdc` | 替換為 v4 條款 |

或對 Cursor：

```text
依 ai-orchestrator/bootstrap.md 補齊 v4 檔案；保留既有 progress 日誌；勿覆寫未備份內容。
```

## 3. 政策變更

| 舊 (v3) | 新 (v4) |
|---------|---------|
| Slack `[handoff]` 派工 | 本機 `docs/tasks/*.md` |
| 掃 `handoff-complete` | 讀 `docs/progress.md` |
| 預設 @ Codex / @Cursor | **禁止**（除非例外） |
| `docs/agent-handoff/tasks/` | `docs/tasks/T-*.md` |

## 4. Slack 頻道

- 更新 Pin charter（`docs/slack-handoff-template.md` §2）
- 公告：本專案改 v4，產品頻道僅 `[status]`
- `#20-agent-*` 改為閒置，不刪頻道

## 5. Legacy 保留

- `docs/agent-handoff/PROTOCOL-v3.md` 可保留作參考
- 新工作不走 v3，除非 `progress.md` 記 `exception: slack-delegate`

## 6. 驗收

[PORTABILITY.md](PORTABILITY.md) 全流程走一輪。

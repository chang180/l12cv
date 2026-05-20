# Workflow v4 — 本機優先、Slack 控進度

**Repo**：`chang180/l12cv`  
**Slack 產品頻道**：`#10-proj-l12cv`（`C0B47UBS2HH`）  
**Bootstrap**：2026-05-20 · skill `ai-orchestrator` v4

## 原則

1. **Source of truth**：`docs/progress.md` + `docs/tasks/*.md`（GitHub 上可見）。
2. **編排者**：**Cursor IDE**（Slack MCP + `gh` + git）。Slack 的 `@Cursor` 為 Cloud Agent，**預設不用於派工**。
3. **Slack 產品頻道**：進度看板 — 發 `[status]`、`[decision]`；**預設禁止** `[handoff]` execute。
4. **本機執行**：讀任務檔 → 在本機 Cursor / Claude Code / subagent 實作 → commit → push。
5. **先 push 再鏡像**：`progress.md` 更新 commit 後，可選發 Slack `[status]`（**勿 @ bot**）。

## 標準循環

```
plan.md（可選）→ docs/tasks/T-xxx.md
→ 本機實作（Cursor IDE）
→ 更新 task status + docs/progress.md → git push
→ [可選] Slack [status] 至 #10-proj-l12cv
→ PR / merge → 再 [status]
```

## 文件結構

| 路徑 | 用途 |
|------|------|
| `docs/WORKFLOW-v4.md` | 本檔（主協定） |
| `docs/progress.md` | 執行進度 SoT |
| `docs/tasks/_TEMPLATE.md` | 任務檔範本 |
| `docs/tasks/T-*.md` | 單一可執行任務包 |
| `docs/decisions/*.md` | 重大決策（可鏡像 `#42-knowledge-decisions`） |
| `docs/agent-roster.md` | Slack bot / 頻道 ID |
| `docs/slack-handoff-template.md` | `[status]` 與 charter |

## Slack 訊息（產品頻道）

| 標籤 | 用途 | 預設 |
|------|------|------|
| `[status]` | 進度快照 | **常用** |
| `[feature]` `[bug]` … | 需求討論 | 可選 |
| `[decision]` | 決策一句 + 連到 `docs/decisions/` | 可選 |
| `[handoff]` | Slack `@executor` 派工 | **禁用**（例外見下） |

`[status]` 範本見 [slack-handoff-template.md](slack-handoff-template.md)。

## 本機多 AI

| 工具 | 角色 | 觸發 |
|------|------|------|
| Cursor IDE | 編排、改碼、`gh` | 永遠開啟本 repo |
| Claude Code | 審閱、改碼 | 本機；官方 Slack thread 見 `progress.md` |
| GitHub Copilot | 實作 PR | 本機；**不**預設 Slack 派工 |

## Slack 例外（進階）

僅當 `docs/progress.md` 記錄 `exception: slack-delegate` 時，可參考 legacy [agent-handoff/PROTOCOL-v3.md](agent-handoff/PROTOCOL-v3.md)。

## 與 v3 差異

| 維度 | v3（legacy） | v4（本檔） |
|------|--------------|------------|
| 開發觸發 | Slack `@executor` | 本機讀 `docs/tasks/*.md` |
| 進度 | 掃 `handoff-complete` | `docs/progress.md` |
| Slack 主訊息 | `[handoff]` | **`[status]`** |

遷移見 `.cursor/skills/ai-orchestrator/MIGRATION-v3-to-v4.md`。

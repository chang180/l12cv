# Workflow v4（Skill 精簡版）

> 完整版：bootstrap 後的 `docs/WORKFLOW-v4.md`（模板 `templates/docs/WORKFLOW-v4-LOCAL-FIRST.md.tpl`）

## 一句話

**本機 Cursor 編排 + GitHub 文件 SoT + Slack 只發 `[status]`。**

## 循環

```
docs/tasks/T-xxx.md → 本機實作 → docs/progress.md push
→ [可選] Slack [status]（無 @ bot）
```

## 禁止（預設）

- 產品頻道 `[handoff]` @ Codex / @Cursor / Claude / @github
- 把 `handoff-complete` @Cursor
- 從 demo repo 複製 `docs/`（只 copy skills + bootstrap）

## 例外

`docs/progress.md` 記錄 `exception: slack-delegate` → 見 optional `docs/agent-handoff/PROTOCOL-v3.md`

## Legacy

舊 [handoff-protocol.md](handoff-protocol.md) 內容已併入本檔與 PROTOCOL-v3；新專案請用本檔。

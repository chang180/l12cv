# 編排者複審手冊（Cursor IDE）

## 交付物

| Agent | 形式 | Review |
|-------|------|--------|
| Claude | Slack 審閱 | `slack_read_thread` |
| Codex / @github / @Cursor | PR | `gh pr diff` |

## 觸發

Executor 於 handoff thread 貼：

```text
handoff-complete
task: T1
status: ready_for_review
pr: <url|n/a>
notes: 已完成，請通知 Cursor IDE 編排者複審。
```

## 複審

```bash
gh pr list --repo chang180/l12cv --state open
gh pr diff <num>
# slack_read_thread channel_id=C0B47UBS2HH message_ts=<parent_ts>
```

## 通過 / 打回

- **通過**：PR 符合任務、Claude `verdict: pass`
- **打回**：thread @ executor 附修正
- 更新 `docs/progress.md`、issue 留言

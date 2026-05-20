# Slack 模板（devstream-core · v4）

主協定：[WORKFLOW-v4.md](WORKFLOW-v4.md)。Legacy 見 [agent-handoff/PROTOCOL-v3.md](agent-handoff/PROTOCOL-v3.md)。

---

## 1. `[status]` — 進度看板（預設）

**發至 `#10-proj-l12cv`。勿 @ 任何 bot。**

```text
[status] chang180/l12cv · main

Phase: bootstrap | planning | implementation | review | done
Tasks: T-001 in_progress
PR: n/a
Blocked: 無
SoT: https://github.com/chang180/l12cv/blob/main/docs/progress.md

（本專案預設本機編排；不在此頻道 @ bot 派工。）
```

---

## 2. 產品頻道 Charter（Pin 用）

```text
📌 Channel Charter — #10-proj-l12cv（v4）

Repo: chang180/l12cv
用途: l12cv — 進度看板 + 需求討論（本機編排）

標籤: [feature] [bug] [release] [ux] [infra] · [status] [decision]
編排: Cursor IDE 本機；Slack 不預設 [handoff] execute

文件: docs/WORKFLOW-v4.md · docs/progress.md · docs/tasks/

👉 請 Pin 本則。
```

---

## 3. Legacy — Slack execute

> 預設禁用。見 `docs/progress.md` 的 `exception: slack-delegate` 與 PROTOCOL-v3。

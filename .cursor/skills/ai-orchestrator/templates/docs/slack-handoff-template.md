# Slack 模板（{{SLACK_WORKSPACE}} · v4）

主協定：[WORKFLOW-v4.md](WORKFLOW-v4.md)。Legacy Slack 派工見 [agent-handoff/PROTOCOL-v3.md](agent-handoff/PROTOCOL-v3.md)（optional）。

---

## 1. `[status]` — 進度看板（預設、產品頻道）

**由 Cursor IDE 發至 `#{{SLACK_PROJECT_CHANNEL}}`。勿 @ 任何 bot。**

```text
[status] {{GITHUB_FULL_REPO}} · {{branch_or_main}}

Phase: bootstrap | planning | implementation | review | done
Tasks: T-001 in_progress, T-002 planned
PR: https://github.com/.../pull/N  (或 n/a)
Blocked: （無｜一句）
SoT: https://github.com/{{GITHUB_OWNER}}/{{GITHUB_REPO}}/blob/{{branch}}/docs/progress.md

（本專案預設本機編排；不在此頻道 @ bot 派工。）
```

---

## 2. 產品頻道 Charter（Pin 用）

```text
📌 Channel Charter — #{{SLACK_PROJECT_CHANNEL}}

Repo: {{GITHUB_FULL_REPO}}
用途: {{PRODUCT_NAME}} — 進度看板 + 需求討論

標籤: [feature] [bug] [release] [ux] [infra] · [status] [decision]
編排: Cursor IDE 本機；Slack 不預設 [handoff] execute

文件: docs/WORKFLOW-v4.md · docs/progress.md · docs/tasks/

👉 請 Pin 本則。
```

---

## 3. `[feature]` / `[bug]`（可選）

```text
[feature]

（描述）

Repo: {{GITHUB_FULL_REPO}}
Issue: （可選）
```

---

## 4. `[decision]` — 決策鏡像

```text
[decision]

Decision: （一句）
Why: （背景）
Tradeoff: （取捨）

詳見: docs/decisions/YYYY-MM-DD-topic.md
```

可複製完整內容至 `#42-knowledge-decisions`。

---

## 5. Legacy — Slack execute（進階例外）

> **預設禁用。** 僅當 `docs/progress.md` 有 `exception: slack-delegate` 時使用。  
> 完整模板見 optional `docs/agent-handoff/PROTOCOL-v3.md`。

要點：先 push `docs/tasks/*.md`；末尾加 `Reply in Traditional Chinese (zh-TW).`；勿把 `handoff-complete` @Cursor。

---

## 變數

| 變數 | 說明 |
|------|------|
| `{{GITHUB_FULL_REPO}}` | bootstrap 寫入之 `owner/repo` |
| `{{SLACK_PROJECT_CHANNEL}}` | 本專案產品頻道名 |
| `{{branch}}` | 目前工作 branch |

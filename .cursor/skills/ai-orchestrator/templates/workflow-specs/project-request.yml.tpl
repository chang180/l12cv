name: {{GITHUB_REPO}}-request
version: 0.2.0

metadata:
  owner: {{GITHUB_OWNER}}
  orchestrator: cursor
  advisory: chatgpt
  transport: slack
  repository: {{GITHUB_FULL_REPO}}
  slack_channel:
    name: {{SLACK_PROJECT_CHANNEL}}
    id: {{SLACK_PROJECT_CHANNEL_ID}}

trigger:
  type: manual
  source: slack_shortcut
  command: /{{GITHUB_REPO}}-request
  note: Workflow Builder 需人工建立，見 {{GITHUB_REPO}}-request.md

steps:
  - id: intake
    action: slack.thread.read
    executor: cursor
    input:
      channel_id: {{SLACK_PROJECT_CHANNEL_ID}}

  - id: create_issue
    action: github.issue.create
    executor: cursor

  - id: notify_agents
    action: slack.thread.message
    note: notify_handoff — 非自動 sub-agent 佇列
    executor: cursor
    input:
      mentions_from: docs/agent-roster.md

  - id: update_progress
    action: github.file.update
    executor: cursor
    input:
      path: docs/progress.md

  - id: setup_workflow_shortcut
    action: slack.workflow.builder
    executor: manual
    input:
      runbook: .workflow-specs/{{GITHUB_REPO}}-request.md

outputs:
  - slack_thread
  - github_issue
  - github_pull_request

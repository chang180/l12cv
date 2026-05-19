#!/bin/bash
# Paste into Claude Code on the web → Environment → Setup script
# See: https://code.claude.com/docs/en/claude-code-on-the-web#setup-scripts
set -euo pipefail

cd "${CLAUDE_PROJECT_DIR:-.}"

COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist

if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate --no-interaction
fi

if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
fi

php artisan migrate --no-interaction --force

npm install
npm run build

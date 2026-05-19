#!/bin/bash
set -euo pipefail

if [ "${CLAUDE_CODE_REMOTE:-}" != "true" ]; then
  exit 0
fi

cd "$CLAUDE_PROJECT_DIR"

if [ ! -d vendor ]; then
  COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist || true
fi

if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate --no-interaction || true
fi

if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
fi

php artisan migrate --no-interaction --force || true

if [ ! -d node_modules ]; then
  npm install || true
fi

if [ ! -f public/build/manifest.json ]; then
  npm run build || true
fi

exit 0

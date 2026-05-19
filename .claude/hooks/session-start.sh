#!/bin/bash
set -euo pipefail

# Only run in remote Claude Code on the web environments
if [ "${CLAUDE_CODE_REMOTE:-}" != "true" ]; then
  exit 0
fi

cd "$CLAUDE_PROJECT_DIR"

# Install PHP dependencies
COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist

# Set up .env if it doesn't exist
if [ ! -f ".env" ]; then
  cp .env.example .env
  php artisan key:generate --no-interaction
fi

# Create SQLite database if it doesn't exist
if [ ! -f "database/database.sqlite" ]; then
  touch database/database.sqlite
fi

# Run migrations
php artisan migrate --no-interaction --force

# Install Node.js dependencies
npm install

# Build frontend assets
npm run build

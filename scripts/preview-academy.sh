#!/usr/bin/env bash
set -Eeuo pipefail

# Run inside the existing Codespace repository. Never reset, clean or reseed.
cd "$(git rev-parse --show-toplevel)"
test -f artisan
test -f .env
command -v php >/dev/null
command -v composer >/dev/null
academy_backup=""
trap 'printf "\nUpdate stopped. Your .env and database were not reset. Code backup stash: %s\nSend the error above before retrying.\n" "${academy_backup:-none needed}"' ERR

git fetch origin
academy_target="$(git rev-parse refs/remotes/origin/feature/academy-teacher-workspace)"
if [[ -n "$(git status --porcelain)" ]]; then
    git stash push --include-untracked -m "Before connected academy preview $(date -u +%Y%m%dT%H%M%SZ)"
    academy_backup="$(git rev-parse refs/stash)"
    printf 'Code backup saved as stash %s (kept after applying).\n' "$academy_backup"
fi
if git show-ref --verify --quiet refs/heads/academy-connected; then
    git switch academy-connected
    git merge --ff-only "$academy_target"
else
    git switch -c academy-connected "$academy_target"
fi
if [[ -n "$academy_backup" ]]; then
    # Keep compiled assets and the local preview URL fix. Stop on any conflict.
    git stash apply "$academy_backup"
fi
composer install --no-interaction
php artisan migrate --force
php artisan config:clear
php artisan route:clear
php artisan view:clear
printf '\nAcademy preview updated. Open your existing website at /learning/academy and refresh.\nNo student data was reseeded. Your code backup stash is retained.\n'

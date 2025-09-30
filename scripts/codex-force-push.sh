#!/bin/bash
set -euo pipefail
BRANCH="${1:-main}"
echo "⚠️  Forzo pubblicazione della versione locale su $BRANCH"
git fetch origin "$BRANCH"
git add -A
git commit -m "Force update da Codex (override remoto)" || true
git push origin "$BRANCH" --force

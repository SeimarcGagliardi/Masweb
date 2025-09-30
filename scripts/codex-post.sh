#!/bin/bash
set -euo pipefail
BRANCH="${1:-main}"

if git diff --quiet && git diff --cached --quiet; then
  echo "ℹ️  Nessuna modifica da pubblicare."
  exit 0
fi

echo "🧾 [POST] Preparo commit…"
git add -A
git commit -m "Aggiornamenti automatici da ChatGPT Codex" || true

echo "📤 [POST] Push su $BRANCH…"
git push origin "$BRANCH"

echo "🚀 [POST] Pubblicato!"

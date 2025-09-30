#!/bin/bash
set -euo pipefail
BRANCH="${1:-main}"

echo "🔄 [PRE] Aggiorno dal remoto ($BRANCH)…"
git fetch origin "$BRANCH"

git pull --rebase --autostash origin "$BRANCH" || true

if git rebase --show-current-patch >/dev/null 2>&1; then
  echo "⚠️  Conflitti durante il rebase: risolvo forzando i wizard su versione locale…"
  for f in     resources/views/livewire/movimenti/carico-wizard.blade.php     resources/views/livewire/movimenti/conto-lavoro-wizard.blade.php     resources/views/livewire/movimenti/scarico-wizard.blade.php     resources/views/livewire/movimenti/transfer-wizard.blade.php
  do
    if [ -e "$f" ]; then
      git checkout --ours -- "$f" || true
      git add "$f" || true
    fi
  done
  git rebase --continue || true
fi

echo "✅ [PRE] Repo allineato. Codex può generare i file."

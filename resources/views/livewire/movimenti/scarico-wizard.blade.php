<div class="mx-auto max-w-6xl px-4 py-6">
  <div class="wizard-frame wizard-grid-overlay">
    <div class="wizard-surface" wire:key="scarico-step-shell">
      <div class="wizard-hero">
        <div class="space-y-3">
          <span class="wizard-hero-badge bg-rose-100 text-rose-700">Scarico / Reso</span>
          <h1 class="text-3xl font-semibold text-slate-900">Gestisci il prelievo di filati con uno stile da sartoria digitale</h1>
          <p class="text-sm text-slate-600 leading-relaxed">
            Scegli il magazzino di origine, indica l'operatore e specifica gli articoli da movimentare.
            L'interfaccia ottimizzata permette di lavorare rapidamente anche da tablet o smartphone.
          </p>
        </div>
        <div class="hidden lg:block">
          <img src="{{ asset('images/loom-weave.svg') }}" alt="Telaio stilizzato" class="h-48 w-auto drop-shadow-xl" />
        </div>
      </div>

      @php
        $wizardSteps = [
          1 => 'Dati prelievo',
          2 => 'Articoli',
          3 => 'Riepilogo',
        ];
      @endphp

      <div class="wizard-stepper">
        <ol>
          @foreach($wizardSteps as $i => $label)
            <li>
              <div class="wizard-step-indicator {{ $step >= $i ? 'border-rose-500 bg-rose-500 text-white shadow-lg shadow-rose-200/80' : 'border-slate-200 bg-white text-slate-500' }}">
                {{ $i }}
              </div>
              <div class="flex flex-col">
                <span class="font-semibold {{ $step >= $i ? 'text-slate-900' : 'text-slate-500' }}">{{ $label }}</span>
                <span class="text-xs text-slate-400">Passo {{ $i }} di {{ count($wizardSteps) }}</span>
              </div>
            </li>
          @endforeach
        </ol>
      </div>

      @if ($errors->has('general'))
        <div class="wizard-error">{{ $errors->first('general') }}</div>
      @endif
      @if (session('ok'))
        <div class="wizard-success">{{ session('ok') }}</div>
      @endif

      <div class="space-y-8" wire:key="scarico-step-{{ $step }}">
        @if($step === 1)
          <div class="wizard-panel">
            <div class="grid gap-5 lg:grid-cols-2">
              <div class="space-y-4">
                <div class="flex flex-col gap-2">
                  <label class="block text-sm font-medium text-slate-700">Tipologia movimento</label>
                  <div class="flex gap-2">
                    <label class="flex-1 cursor-pointer rounded-2xl border border-rose-200 bg-white/80 px-3 py-2 text-center text-sm font-medium {{ $contesto['tipo']==='prelievo' ? 'ring-2 ring-rose-400 text-rose-700' : 'text-slate-600' }}">
                      <input type="radio" class="hidden" value="prelievo" wire:model.live="contesto.tipo" />
                      Prelievo interno
                    </label>
                    <label class="flex-1 cursor-pointer rounded-2xl border border-emerald-200 bg-white/80 px-3 py-2 text-center text-sm font-medium {{ $contesto['tipo']==='reso' ? 'ring-2 ring-emerald-400 text-emerald-700' : 'text-slate-600' }}">
                      <input type="radio" class="hidden" value="reso" wire:model.live="contesto.tipo" />
                      Reso materiale
                    </label>
                  </div>
                  @error('contesto.tipo')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="space-y-2">
                  <label class="block text-sm font-medium text-slate-700">Magazzino di origine</label>
                  <select wire:model.live="contesto.magazzino_id" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-rose-500 focus:ring-rose-500">
                    <option value="">— seleziona —</option>
                    @foreach($magazzini as $m)
                      <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
                    @endforeach
                  </select>
                  @error('contesto.magazzino_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                @if($ubicazioni->isNotEmpty())
                  <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">Ubicazione</label>
                    <select wire:model.live="contesto.ubicazione_id" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-rose-500 focus:ring-rose-500">
                      <option value="">— seleziona —</option>
                      @foreach($ubicazioni as $u)
                        <option value="{{ $u->id }}">{{ $u->codice }} — {{ $u->descrizione }}</option>
                      @endforeach
                    </select>
                    @error('contesto.ubicazione_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                  </div>
                @elseif($contesto['magazzino_id'])
                  <p class="text-xs text-slate-500">Nessuna ubicazione attiva per questo magazzino.</p>
                @endif
              </div>

              <div class="space-y-4">
                <div class="grid gap-3 sm:grid-cols-2">
                  <div>
                    <label class="block text-sm font-medium text-slate-700">Operatore</label>
                    <input type="text" wire:model.live="contesto.operatore" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-rose-500 focus:ring-rose-500" placeholder="Nome operatore" />
                    @error('contesto.operatore')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700">Commessa / Rif.</label>
                    <input type="text" wire:model.live="contesto.commessa" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-rose-500 focus:ring-rose-500" />
                    @error('contesto.commessa')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700">Destinatario</label>
                    <input type="text" wire:model.live="contesto.destinatario" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-rose-500 focus:ring-rose-500" />
                    @error('contesto.destinatario')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700">Note</label>
                  <textarea wire:model.live="contesto.note" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-rose-500 focus:ring-rose-500" placeholder="Specifiche per il magazzino..."></textarea>
                  @error('contesto.note')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
              </div>
            </div>
          </div>
        @endif

        @if($step === 2)
          <div class="wizard-panel space-y-6">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
              <div>
                <h2>Articoli in uscita</h2>
                <p class="text-sm text-slate-500">Indica filati, tessuti o accessori prelevati dal magazzino.</p>
              </div>
              <button type="button" wire:click="addRiga" class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-4 py-2 text-sm font-medium text-rose-700 shadow-sm transition hover:bg-rose-200">
                <span class="text-lg">＋</span> Aggiungi riga
              </button>
            </div>

            <div class="space-y-5">
              @foreach($righe as $i => $riga)
                <div class="rounded-3xl border border-rose-100/80 bg-white/90 p-5 shadow-inner" wire:key="scarico-riga-{{ $i }}">
                  <div class="grid gap-4 md:grid-cols-12 md:items-end">
                    <div class="md:col-span-6">
                      <label class="block text-sm font-medium text-slate-700">Articolo</label>
                      <select wire:model.live="righe.{{ $i }}.articolo_id" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-rose-500 focus:ring-rose-500">
                        <option value="">— seleziona —</option>
                        @foreach($articoli as $a)
                          <option value="{{ $a->id }}">{{ $a->codice }} — {{ $a->descrizione }}</option>
                        @endforeach
                      </select>
                      @error("righe.$i.articolo_id")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-3">
                      <label class="block text-sm font-medium text-slate-700">Q.tà</label>
                      <input type="number" step="0.001" min="0" wire:model.live="righe.{{ $i }}.qta" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-rose-500 focus:ring-rose-500" />
                      @error("righe.$i.qta")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                      <label class="block text-sm font-medium text-slate-700">Lotto</label>
                      <input type="text" wire:model.live="righe.{{ $i }}.lotto" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-rose-500 focus:ring-rose-500" />
                    </div>
                    <div class="md:col-span-1">
                      <button type="button" wire:click="removeRiga({{ $i }})" class="w-full rounded-2xl border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-600 transition hover:bg-rose-100">🗑️</button>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        @if($step === 3)
          <div class="wizard-panel space-y-6">
            <div>
              <h2>Riepilogo movimento</h2>
              <p class="text-sm text-slate-500">Controlla le informazioni prima di registrare lo scarico.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 text-sm">
              <div class="rounded-2xl border border-rose-100/70 bg-rose-50/60 p-4">
                <div class="text-xs font-semibold uppercase tracking-wide text-rose-600">Magazzino</div>
                <div class="text-base font-semibold text-slate-800">{{ $riepilogo['magazzino'] ?? '—' }}</div>
                @if($riepilogo['ubicazione'] ?? false)
                  <div class="text-xs text-slate-500">Ubicazione: {{ $riepilogo['ubicazione'] }}</div>
                @endif
              </div>
              <div class="rounded-2xl border border-slate-200 bg-white/70 p-4">
                <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Operatore</div>
                <div class="text-base font-semibold text-slate-800">{{ $contesto['operatore'] }}</div>
                <div class="text-xs text-slate-500">Destinatario: {{ $contesto['destinatario'] ?: '—' }}</div>
              </div>
            </div>
            <div class="overflow-hidden rounded-3xl border border-slate-200/60">
              <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50/60">
                  <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Articolo</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-600">Q.tà</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-600">Lotto</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white/80">
                  @foreach($riepilogo['righe'] ?? [] as $r)
                    <tr>
                      <td class="px-4 py-3">{{ $r['codice'] }} — {{ $r['descrizione'] }}</td>
                      <td class="px-4 py-3 text-right font-medium text-slate-700">{{ $r['qta'] }}</td>
                      <td class="px-4 py-3 text-slate-500">{{ $r['lotto'] ?: '—' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @endif
      </div>

      <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
        <button type="button" class="btn-secondary" wire:click="back" wire:target="back" wire:loading.attr="disabled" @disabled($step===1)>
          Indietro
        </button>
        @if($step < 3)
          <button type="button" class="btn-primary bg-gradient-to-r from-rose-500 to-rose-600" wire:click="next" wire:target="next" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="next">Avanti</span>
            <span wire:loading wire:target="next">Controllo dati…</span>
          </button>
        @else
          <button type="button" class="btn-primary bg-gradient-to-r from-rose-500 to-rose-600" wire:click="conferma" wire:target="conferma" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="conferma">Conferma movimento</span>
            <span wire:loading wire:target="conferma">Salvataggio…</span>
          </button>
        @endif
      </div>
    </div>
  </div>
</div>

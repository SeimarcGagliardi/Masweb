<div class="mx-auto max-w-6xl px-4 py-6">
  <div class="wizard-frame wizard-grid-overlay">
    <div class="wizard-surface" wire:key="conto-step-shell">
      <div class="wizard-hero">
        <div class="space-y-3">
          <span class="wizard-hero-badge bg-violet-100 text-violet-700">Conto lavoro</span>
          <h1 class="text-3xl font-semibold text-slate-900">Coordina invii e rientri ai terzisti con stile sartoriale</h1>
          <p class="text-sm text-slate-600 leading-relaxed">
            Passa da invio a rientro in un click, compila i dati e ottieni un riepilogo chiaro con colori ispirati alla maglieria.
          </p>
        </div>
        <div class="hidden lg:block">
          <img src="{{ asset('images/loom-weave.svg') }}" alt="Telaio di maglieria" class="h-48 w-auto drop-shadow-xl" />
        </div>
      </div>

      <div class="flex flex-wrap items-center justify-between gap-3 rounded-3xl border border-white/70 bg-white/70 p-3 shadow-inner">
        <div class="text-sm font-medium text-slate-700">Fase di lavoro</div>
        <div class="flex gap-2">
          <button type="button" wire:click="switchFase('invio')" class="rounded-full px-4 py-2 text-sm font-semibold transition {{ $fase === 'invio' ? 'bg-violet-500 text-white shadow-lg' : 'bg-white/80 text-slate-600 hover:bg-slate-100' }}">Invio ai terzisti</button>
          <button type="button" wire:click="switchFase('rientro')" class="rounded-full px-4 py-2 text-sm font-semibold transition {{ $fase === 'rientro' ? 'bg-violet-500 text-white shadow-lg' : 'bg-white/80 text-slate-600 hover:bg-slate-100' }}">Rientro lavorazioni</button>
        </div>
      </div>

      @php($labels = $fase === 'invio' ? [1=>'Dati invio',2=>'Articoli',3=>'Riepilogo'] : [1=>'Ordine e magazzino',2=>'Quantità rientro',3=>'Riepilogo'])

      <div class="wizard-stepper">
        <ol>
          @foreach ($labels as $i => $label)
            <li>
              <div class="wizard-step-indicator {{ $step >= $i ? 'border-violet-500 bg-violet-500 text-white shadow-lg shadow-violet-200/80' : 'border-slate-200 bg-white text-slate-500' }}">
                {{ $i }}
              </div>
              <div class="flex flex-col">
                <span class="font-semibold {{ $step >= $i ? 'text-slate-900' : 'text-slate-500' }}">{{ $label }}</span>
                <span class="text-xs text-slate-400">Passo {{ $i }} di {{ count($labels) }}</span>
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

      <div class="space-y-8" wire:key="conto-step-{{ $fase }}-{{ $step }}">
        @if($fase === 'invio')
          @if($step === 1)
            <div class="wizard-panel space-y-6">
              <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label class="block text-sm font-medium text-slate-700">Terzista</label>
                  <select wire:model.live="invio.terzista_id" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500">
                    <option value="">— seleziona —</option>
                    @foreach($terzisti as $t)
                      <option value="{{ $t->id }}">{{ $t->ragione_sociale }}</option>
                    @endforeach
                  </select>
                  @error('invio.terzista_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-2">
                  <label class="block text-sm font-medium text-slate-700">Magazzino di uscita</label>
                  <select wire:model.live="invio.magazzino_id" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500">
                    <option value="">— seleziona —</option>
                    @foreach($magazzini as $m)
                      <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
                    @endforeach
                  </select>
                  @error('invio.magazzino_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-2">
                  <label class="block text-sm font-medium text-slate-700">Ubicazione</label>
                  <select wire:model.live="invio.ubicazione_id" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500">
                    <option value="">— seleziona —</option>
                    @foreach($ubicazioniInvio as $u)
                      <option value="{{ $u->id }}">{{ $u->codice }} — {{ $u->descrizione }}</option>
                    @endforeach
                  </select>
                  @error('invio.ubicazione_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                  <div>
                    <label class="block text-sm font-medium text-slate-700">Data invio</label>
                    <input type="date" wire:model.live="invio.data_invio" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500" />
                    @error('invio.data_invio')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700">Rientro previsto</label>
                    <input type="date" wire:model.live="invio.data_rientro_prevista" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500" />
                    @error('invio.data_rientro_prevista')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                  </div>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700">Note per il terzista</label>
                <textarea wire:model.live="invio.note" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500" placeholder="Indicazioni, componenti, urgenze..."></textarea>
                @error('invio.note')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
              </div>
            </div>
          @endif

          @if($step === 2)
            <div class="wizard-panel space-y-6">
              <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div>
                  <h2>Articoli da inviare</h2>
                  <p class="text-sm text-slate-500">Elenca i materiali destinati al terzista.</p>
                </div>
                <button type="button" class="inline-flex items-center gap-2 rounded-full bg-violet-100 px-4 py-2 text-sm font-medium text-violet-700 shadow-sm transition hover:bg-violet-200" wire:click="addRiga">
                  <span class="text-lg">＋</span> Aggiungi riga
                </button>
              </div>

              <div class="space-y-5">
                @foreach($righe as $i => $riga)
                  <div class="rounded-3xl border border-violet-100/80 bg-white/90 p-5 shadow-inner" wire:key="invio-riga-{{ $i }}">
                    <div class="grid gap-4 lg:grid-cols-12 lg:items-end">
                      <div class="lg:col-span-5">
                        <label class="block text-sm font-medium text-slate-700">Articolo</label>
                        <select wire:model.live="righe.{{ $i }}.articolo_id" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500">
                          <option value="">— seleziona —</option>
                          @foreach($articoli as $a)
                            <option value="{{ $a->id }}">{{ $a->codice }} — {{ $a->descrizione }}</option>
                          @endforeach
                        </select>
                        @error("righe.$i.articolo_id")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                      </div>
                      <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Q.tà</label>
                        <input type="number" step="0.001" min="0" wire:model.live="righe.{{ $i }}.qta" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500" />
                        @error("righe.$i.qta")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                      </div>
                      <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Lotto</label>
                        <input type="text" wire:model.live="righe.{{ $i }}.lotto" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500" />
                      </div>
                      <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-slate-700">Componenti / lavorazioni</label>
                        <input type="text" wire:model.live="righe.{{ $i }}.componenti" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500" placeholder="Es. fodera, bottoni..." />
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
                <h2>Riepilogo invio</h2>
                <p class="text-sm text-slate-500">Conferma le informazioni prima di registrare la spedizione.</p>
              </div>
              <div class="grid gap-4 md:grid-cols-2 text-sm">
                <div class="rounded-2xl border border-violet-100/70 bg-violet-50/60 p-4">
                  <div class="text-xs font-semibold uppercase tracking-wide text-violet-600">Terzista</div>
                  <div class="text-base font-semibold text-slate-800">{{ $riepilogo['terzista'] ?? '—' }}</div>
                  <div class="text-xs text-slate-500">Magazzino: {{ $riepilogo['magazzino'] ?? '—' }}</div>
                </div>
                <div class="rounded-2xl border border-amber-100/70 bg-amber-50/60 p-4">
                  <div class="text-xs font-semibold uppercase tracking-wide text-amber-600">Date</div>
                  <div class="text-base font-semibold text-slate-800">Invio: {{ $invio['data_invio'] }}</div>
                  <div class="text-xs text-slate-500">Rientro previsto: {{ $invio['data_rientro_prevista'] ?: '—' }}</div>
                </div>
              </div>
              <div class="overflow-hidden rounded-3xl border border-slate-200/60">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                  <thead class="bg-slate-50/60">
                    <tr>
                      <th class="px-4 py-3 text-left font-semibold text-slate-600">Articolo</th>
                      <th class="px-4 py-3 text-right font-semibold text-slate-600">Q.tà</th>
                      <th class="px-4 py-3 text-left font-semibold text-slate-600">Lotto</th>
                      <th class="px-4 py-3 text-left font-semibold text-slate-600">Componenti</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 bg-white/80">
                    @foreach($riepilogo['righe'] ?? [] as $r)
                      <tr>
                        <td class="px-4 py-3">{{ $r['codice'] }} — {{ $r['descrizione'] }}</td>
                        <td class="px-4 py-3 text-right font-medium text-slate-700">{{ $r['qta'] }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $r['lotto'] ?: '—' }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $r['componenti'] ?: '—' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endif
        @else
          @if($step === 1)
            <div class="wizard-panel space-y-6">
              <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label class="block text-sm font-medium text-slate-700">Ordine conto lavoro</label>
                  <select wire:model.live="rientro.ordine_id" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500">
                    <option value="">— seleziona —</option>
                    @foreach($ordiniAperti as $ordine)
                      <option value="{{ $ordine->id }}">#{{ $ordine->id }} — {{ $ordine->terzista?->ragione_sociale }} ({{ $ordine->stato }})</option>
                    @endforeach
                  </select>
                  @error('rientro.ordine_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-2">
                  <label class="block text-sm font-medium text-slate-700">Magazzino di rientro</label>
                  <select wire:model.live="rientro.magazzino_id" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500">
                    <option value="">— seleziona —</option>
                    @foreach($magazzini as $m)
                      <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
                    @endforeach
                  </select>
                  @error('rientro.magazzino_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div class="space-y-2">
                  <label class="block text-sm font-medium text-slate-700">Ubicazione</label>
                  <select wire:model.live="rientro.ubicazione_id" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500">
                    <option value="">— seleziona —</option>
                    @foreach($ubicazioniRientro as $u)
                      <option value="{{ $u->id }}">{{ $u->codice }} — {{ $u->descrizione }}</option>
                    @endforeach
                  </select>
                  @error('rientro.ubicazione_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700">Note di rientro</label>
                <textarea wire:model.live="rientro.note" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500" placeholder="Eventuali annotazioni..."></textarea>
                @error('rientro.note')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
              </div>
            </div>
          @endif

          @if($step === 2)
            <div class="wizard-panel space-y-6">
              <div>
                <h2>Quantità di rientro</h2>
                <p class="text-sm text-slate-500">Indica quanto rientra e gli eventuali scarti per ogni riga dell'ordine.</p>
              </div>
              <div class="space-y-5">
                @foreach($rientroRighe as $i => $riga)
                  <div class="rounded-3xl border border-violet-100/80 bg-white/90 p-5 shadow-inner" wire:key="rientro-riga-{{ $riga['id'] }}">
                    <div class="grid gap-4 lg:grid-cols-12 lg:items-end">
                      <div class="lg:col-span-4">
                        <div class="text-sm font-semibold text-slate-800">{{ $riga['articolo'] }}</div>
                        <div class="text-xs text-slate-500">Inviato: {{ $riga['qta_inviata'] }} — Disponibile: {{ $riga['disponibile'] }}</div>
                      </div>
                      <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-slate-700">Rientro</label>
                        <input type="number" step="0.001" min="0" max="{{ $riga['disponibile'] }}" wire:model.live="rientroRighe.{{ $i }}.qta_rientro" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500" />
                        @error("rientroRighe.$i.qta_rientro")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                      </div>
                      <div class="lg:col-span-3">
                        <label class="block text-sm font-medium text-slate-700">Scarto</label>
                        <input type="number" step="0.001" min="0" wire:model.live="rientroRighe.{{ $i }}.scarto" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-violet-500 focus:ring-violet-500" />
                        @error("rientroRighe.$i.scarto")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                      </div>
                      <div class="lg:col-span-2">
                        <div class="text-xs text-slate-500">Lotto: {{ $riga['lotto'] ?: '—' }}</div>
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
                <h2>Riepilogo rientro</h2>
                <p class="text-sm text-slate-500">Conferma i dati per chiudere il conto lavoro.</p>
              </div>
              <div class="grid gap-4 md:grid-cols-2 text-sm">
                <div class="rounded-2xl border border-violet-100/70 bg-violet-50/60 p-4">
                  <div class="text-xs font-semibold uppercase tracking-wide text-violet-600">Ordine</div>
                  <div class="text-base font-semibold text-slate-800">#{{ $rientro['ordine_id'] }}</div>
                </div>
                <div class="rounded-2xl border border-emerald-100/70 bg-emerald-50/60 p-4">
                  <div class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Magazzino</div>
                  <div class="text-base font-semibold text-slate-800">{{ optional($magazzini->firstWhere('id', $rientro['magazzino_id']))?->descrizione }}</div>
                </div>
              </div>
              <div class="overflow-hidden rounded-3xl border border-slate-200/60">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                  <thead class="bg-slate-50/60">
                    <tr>
                      <th class="px-4 py-3 text-left font-semibold text-slate-600">Articolo</th>
                      <th class="px-4 py-3 text-right font-semibold text-slate-600">Rientro</th>
                      <th class="px-4 py-3 text-right font-semibold text-slate-600">Scarto</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 bg-white/80">
                    @foreach($rientroRighe as $r)
                      <tr>
                        <td class="px-4 py-3">{{ $r['articolo'] }}</td>
                        <td class="px-4 py-3 text-right font-medium text-slate-700">{{ $r['qta_rientro'] ?: '—' }}</td>
                        <td class="px-4 py-3 text-right text-slate-500">{{ $r['scarto'] ?: '—' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @endif
        @endif
      </div>

      <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
        <button type="button" class="btn-secondary" wire:click="back" wire:target="back" wire:loading.attr="disabled" @disabled($step===1)>
          Indietro
        </button>
        @if($step < 3)
          <button type="button" class="btn-primary bg-gradient-to-r from-violet-500 to-fuchsia-500" wire:click="next" wire:target="next" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="next">Avanti</span>
            <span wire:loading wire:target="next">Controllo dati…</span>
          </button>
        @else
          <button type="button" class="btn-primary bg-gradient-to-r from-violet-500 to-fuchsia-500" wire:click="conferma" wire:target="conferma" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="conferma">Conferma</span>
            <span wire:loading wire:target="conferma">Salvataggio…</span>
          </button>
        @endif
      </div>
    </div>
  </div>
</div>

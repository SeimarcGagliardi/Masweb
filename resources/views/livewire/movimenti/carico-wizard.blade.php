<div class="mx-auto max-w-6xl px-4 py-6">
  <div class="wizard-frame wizard-grid-overlay">
    <div class="wizard-surface" wire:key="carico-step-shell">
      <div class="wizard-hero">
        <div class="space-y-3">
          <span class="wizard-hero-badge">Nuovo carico</span>
          <h1 class="text-3xl font-semibold text-slate-900">
            Registra il carico di filati e accessori in pochi passi
          </h1>
          <p class="text-sm text-slate-600 leading-relaxed">
            Completa i campi richiesti e traccia i movimenti di magazzino con una grafica ispirata alla maglieria.
            I passaggi successivi si attivano automaticamente quando i dati sono validi.
          </p>
        </div>
        <div class="hidden lg:block">
          <img src="{{ asset('images/warehouse-racks.svg') }}" alt="Illustrazione di scaffalature di magazzino" class="h-48 w-auto drop-shadow-xl" />
        </div>
      </div>

      @php
        $wizardSteps = [
          1 => 'Dati generali',
          2 => 'Articoli',
          3 => 'Riepilogo',
        ];
      @endphp

      <div class="wizard-stepper">
        <ol>
          @foreach($wizardSteps as $i => $label)
            <li>
              <div class="wizard-step-indicator {{ $step >= $i ? 'border-emerald-500 bg-emerald-500 text-white shadow-lg shadow-emerald-200/80' : 'border-slate-200 bg-white text-slate-500' }}">
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

      <div class="space-y-8" wire:key="carico-step-{{ $step }}">
        @if($step === 1)
          <div class="wizard-panel">
            <div class="grid gap-5 lg:grid-cols-2">
              <div class="space-y-4">
                <label class="block text-sm font-medium text-slate-700">Magazzino di destinazione</label>
                <select wire:model.live="contesto.magazzino_id" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-emerald-500 focus:ring-emerald-500">
                  <option value="">— seleziona —</option>
                  @foreach($magazzini as $m)
                    <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
                  @endforeach
                </select>
                @error('contesto.magazzino_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror

                @if($ubicazioni->isNotEmpty())
                  <div class="space-y-2">
                    <label class="block text-sm font-medium text-slate-700">Ubicazione interna</label>
                    <select wire:model.live="contesto.ubicazione_id" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-emerald-500 focus:ring-emerald-500">
                      <option value="">— seleziona —</option>
                      @foreach($ubicazioni as $u)
                        <option value="{{ $u->id }}">{{ $u->codice }} — {{ $u->descrizione }}</option>
                      @endforeach
                    </select>
                    @error('contesto.ubicazione_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                  </div>
                @elseif($contesto['magazzino_id'])
                  <p class="text-xs text-slate-500">Il magazzino selezionato non ha ubicazioni attive: il carico sarà registrato a livello magazzino.</p>
                @endif
              </div>

              <div class="space-y-4">
                <div class="grid gap-3 sm:grid-cols-2">
                  <div>
                    <label class="block text-sm font-medium text-slate-700">Commessa</label>
                    <input type="text" wire:model.live="contesto.commessa" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-emerald-500 focus:ring-emerald-500" placeholder="Es. PRJ-2025" />
                    @error('contesto.commessa')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700">Riferimento documento</label>
                    <input type="text" wire:model.live="contesto.riferimento" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-emerald-500 focus:ring-emerald-500" placeholder="DDT, ordine..." />
                    @error('contesto.riferimento')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700">Bagno</label>
                    <input type="text" wire:model.live="contesto.bagno" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-emerald-500 focus:ring-emerald-500" />
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-slate-700">Linea</label>
                    <input type="text" wire:model.live="contesto.linea" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-emerald-500 focus:ring-emerald-500" />
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-700">Note operative</label>
                  <textarea wire:model.live="contesto.note" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-emerald-500 focus:ring-emerald-500" placeholder="Indicazioni per il magazzino..."></textarea>
                </div>
              </div>
            </div>
          </div>
        @endif

        @if($step === 2)
          <div class="wizard-panel space-y-6">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
              <div>
                <h2>Articoli in ingresso</h2>
                <p class="text-sm text-slate-500">Aggiungi le bobine, i filati o gli accessori che entrano in magazzino.</p>
              </div>
              <button type="button" wire:click="addRiga" class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-2 text-sm font-medium text-emerald-700 shadow-sm transition hover:bg-emerald-200">
                <span class="text-lg">＋</span> Aggiungi riga
              </button>
            </div>

            <div class="space-y-5">
              @foreach($righe as $i => $riga)
                <div class="rounded-3xl border border-emerald-100/80 bg-white/90 p-5 shadow-inner" wire:key="carico-riga-{{ $i }}">
                  <div class="grid gap-4 md:grid-cols-12 md:items-end">
                    <div class="md:col-span-6">
                      <label class="block text-sm font-medium text-slate-700">Articolo</label>
                      <select wire:model.live="righe.{{ $i }}.articolo_id" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">— seleziona —</option>
                        @foreach($articoli as $a)
                          <option value="{{ $a->id }}">{{ $a->codice }} — {{ $a->descrizione }}</option>
                        @endforeach
                      </select>
                      @error("righe.$i.articolo_id")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-3">
                      <label class="block text-sm font-medium text-slate-700">Q.tà (kg/pz)</label>
                      <input type="number" step="0.001" min="0" wire:model.live="righe.{{ $i }}.qta" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-emerald-500 focus:ring-emerald-500" />
                      @error("righe.$i.qta")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                      <label class="block text-sm font-medium text-slate-700">Lotto</label>
                      <input type="text" wire:model.live="righe.{{ $i }}.lotto" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 text-sm shadow-inner focus:border-emerald-500 focus:ring-emerald-500" />
                      @error("righe.$i.lotto")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
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
              <h2>Riepilogo carico</h2>
              <p class="text-sm text-slate-500">Verifica i dati prima di confermare il movimento in magazzino.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 text-sm">
              <div class="rounded-2xl border border-emerald-100/70 bg-emerald-50/60 p-4">
                <div class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Magazzino</div>
                <div class="text-base font-semibold text-slate-800">{{ $riepilogo['magazzino'] ?? '—' }}</div>
                @if($riepilogo['ubicazione'] ?? false)
                  <div class="text-xs text-slate-500">Ubicazione: {{ $riepilogo['ubicazione'] }}</div>
                @endif
              </div>
              <div class="rounded-2xl border border-amber-100/70 bg-amber-50/60 p-4">
                <div class="text-xs font-semibold uppercase tracking-wide text-amber-600">Commessa</div>
                <div class="text-base font-semibold text-slate-800">{{ $contesto['commessa'] ?: '—' }}</div>
                <div class="text-xs text-slate-500">Rif: {{ $contesto['riferimento'] ?: '—' }}</div>
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
          <button type="button" class="btn-primary bg-gradient-to-r from-emerald-500 to-emerald-600" wire:click="next" wire:target="next" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="next">Avanti</span>
            <span wire:loading wire:target="next">Controllo dati…</span>
          </button>
        @else
          <button type="button" class="btn-primary bg-gradient-to-r from-emerald-500 to-emerald-600" wire:click="conferma" wire:target="conferma" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="conferma">Conferma carico</span>
            <span wire:loading wire:target="conferma">Salvataggio…</span>
          </button>
        @endif
      </div>
    </div>
  </div>
</div>

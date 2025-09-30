@php
    $stepLabels = $fase === 'invio'
        ? [1 => ['label' => 'Dati invio', 'subtitle' => 'Terzista e magazzino'], 2 => ['label' => 'Articoli', 'subtitle' => 'Quantità e componenti'], 3 => ['label' => 'Riepilogo', 'subtitle' => 'Controlla e conferma']]
        : [1 => ['label' => 'Ordine', 'subtitle' => 'Seleziona rientro'], 2 => ['label' => 'Quantità', 'subtitle' => 'Rientri e scarti'], 3 => ['label' => 'Riepilogo', 'subtitle' => 'Verifica finale']];
@endphp

<div class="wizard-shell min-h-screen bg-gradient-to-br from-violet-50 via-white to-emerald-50 pb-10 pt-6">
    <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 lg:px-6">
        <section class="relative overflow-hidden rounded-3xl border border-white/60 bg-white/85 p-8 shadow-lg backdrop-blur">
            <div class="absolute -left-16 top-0 h-56 w-56 -rotate-12 rounded-full bg-violet-100 blur-3xl"></div>
            <img src="{{ asset('images/loom-weave.svg') }}" alt="Illustrazione conto lavoro" class="absolute -right-10 bottom-0 w-56 opacity-80 md:w-72" aria-hidden="true">
            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-wider text-violet-600">Gestione conto lavoro</p>
                    <h1 class="text-3xl font-semibold text-slate-900">Monitora invii ai terzisti e rientri lavorati con un solo flusso</h1>
                    <p class="max-w-2xl text-sm text-slate-600">Passa rapidamente dall&#39;invio di materiale ai partner esterni al rientro delle lavorazioni. Il percorso guidato ti accompagna su desktop e smartphone.</p>
                    <div class="inline-flex rounded-2xl border border-slate-200/80 bg-white/80 p-1 shadow-sm">
                        <button type="button" wire:click="switchFase('invio')" class="rounded-2xl px-4 py-2 text-sm font-semibold transition @if($fase==='invio') bg-violet-600 text-white shadow @else text-slate-600 hover:text-slate-900 @endif">
                            Invio ai terzisti
                        </button>
                        <button type="button" wire:click="switchFase('rientro')" class="rounded-2xl px-4 py-2 text-sm font-semibold transition @if($fase==='rientro') bg-violet-600 text-white shadow @else text-slate-600 hover:text-slate-900 @endif">
                            Rientro lavorazioni
                        </button>
                    </div>
                </div>
                <div class="grid gap-3 rounded-2xl border border-slate-200/70 bg-white/70 p-4 text-sm text-slate-600 shadow-sm lg:w-80">
                    <div>
                        <p class="font-semibold text-slate-500">Modalità attiva</p>
                        <p class="text-lg font-semibold text-slate-900">{{ $fase === 'invio' ? 'Invio al terzista' : 'Rientro da terzista' }}</p>
                    </div>
                    <p class="text-xs uppercase tracking-wide text-violet-600">Step {{ $step }} di {{ count($stepLabels) }}</p>
                    @if($fase === 'invio')
                        <p class="text-xs text-slate-500">Ultimo terzista selezionato: {{ optional($terzisti->firstWhere('id', $invio['terzista_id']))?->ragione_sociale ?? '—' }}</p>
                    @else
                        <p class="text-xs text-slate-500">Ordine selezionato: {{ $rientro['ordine_id'] ? '#'.$rientro['ordine_id'] : '—' }}</p>
                    @endif
                </div>
            </div>
        </section>

        <div class="rounded-3xl border border-white/60 bg-white/90 p-6 shadow-xl backdrop-blur">
            <ol class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
                @foreach($stepLabels as $index => $info)
                    <li class="relative flex flex-1 items-start gap-3 sm:flex-col sm:items-center sm:text-center">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl border text-sm font-semibold transition @if($step > $index) border-violet-600 bg-violet-600 text-white shadow-md @elseif($step === $index) border-violet-600 bg-violet-50 text-violet-700 shadow @else border-slate-200 bg-white text-slate-400 @endif">
                            {{ $index }}
                        </span>
                        <div class="flex-1 sm:flex sm:flex-col sm:items-center">
                            <p class="text-sm font-semibold text-slate-900">{{ $info['label'] }}</p>
                            <p class="text-xs text-slate-500">{{ $info['subtitle'] }}</p>
                        </div>
                        @if($index < count($stepLabels))
                            <span class="absolute left-5 top-5 hidden h-px w-full translate-y-1/2 bg-gradient-to-r from-slate-200 via-slate-100 to-slate-200 sm:block"></span>
                        @endif
                    </li>
                @endforeach
            </ol>

            <div class="mt-8 space-y-6">
                @if($fase === 'invio')
                    @if($step === 1)
                        <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                            <header class="space-y-1">
                                <h2 class="text-lg font-semibold text-slate-900">Dati per l&#39;invio</h2>
                                <p class="text-sm text-slate-600">Seleziona il terzista e indica da quale magazzino parte la lavorazione.</p>
                            </header>

                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <div class="space-y-2">
                                    <label for="terzista" class="text-sm font-semibold text-slate-700">Terzista</label>
                                    <select id="terzista" wire:model="invio.terzista_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                        <option value="">— seleziona —</option>
                                        @foreach($terzisti as $t)
                                            <option value="{{ $t->id }}">{{ $t->ragione_sociale }}</option>
                                        @endforeach
                                    </select>
                                    @error('invio.terzista_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="magazzino-invio" class="text-sm font-semibold text-slate-700">Magazzino di uscita</label>
                                    <select id="magazzino-invio" wire:model="invio.magazzino_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                        <option value="">— seleziona —</option>
                                        @foreach($magazzini as $m)
                                            <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
                                        @endforeach
                                    </select>
                                    @error('invio.magazzino_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="ubicazione-invio" class="text-sm font-semibold text-slate-700">Ubicazione</label>
                                    <select id="ubicazione-invio" wire:model="invio.ubicazione_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                        <option value="">— seleziona —</option>
                                        @foreach($ubicazioniInvio as $u)
                                            <option value="{{ $u->id }}">{{ $u->codice }} — {{ $u->descrizione }}</option>
                                        @endforeach
                                    </select>
                                    @error('invio.ubicazione_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="data-invio" class="text-sm font-semibold text-slate-700">Data invio</label>
                                    <input id="data-invio" type="date" wire:model="invio.data_invio" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                    @error('invio.data_invio')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="data-rientro" class="text-sm font-semibold text-slate-700">Data rientro prevista</label>
                                    <input id="data-rientro" type="date" wire:model="invio.data_rientro_prevista" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                    @error('invio.data_rientro_prevista')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="note-invio" class="text-sm font-semibold text-slate-700">Note per il terzista</label>
                                <textarea id="note-invio" rows="3" wire:model.lazy="invio.note" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500" placeholder="Componenti, urgenze, istruzioni…"></textarea>
                                @error('invio.note')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                        </section>
                    @endif

                    @if($step === 2)
                        <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <header>
                                    <h2 class="text-lg font-semibold text-slate-900">Articoli da inviare</h2>
                                    <p class="text-sm text-slate-600">Aggiungi ogni riga con quantità, lotto e componenti opzionali.</p>
                                </header>
                                <button type="button" class="btn-secondary rounded-2xl px-4 py-2 text-sm font-semibold text-violet-600 shadow-sm hover:bg-violet-50 hover:text-violet-700" wire:click="addRiga">
                                    + Aggiungi riga
                                </button>
                            </div>

                            <div class="space-y-6">
                                @foreach($righe as $i => $riga)
                                    <article wire:key="invio-riga-{{ $i }}" class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
                                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">
                                            <div class="lg:col-span-5 space-y-2">
                                                <label class="text-sm font-semibold text-slate-700" for="articolo-invio-{{ $i }}">Articolo</label>
                                                <select id="articolo-invio-{{ $i }}" wire:model="righe.{{ $i }}.articolo_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                                    <option value="">— seleziona —</option>
                                                    @foreach($articoli as $a)
                                                        <option value="{{ $a->id }}">{{ $a->codice }} — {{ $a->descrizione }}</option>
                                                    @endforeach
                                                </select>
                                                @error("righe.$i.articolo_id")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                            </div>
                                            <div class="lg:col-span-2 space-y-2">
                                                <label class="text-sm font-semibold text-slate-700" for="qta-invio-{{ $i }}">Q.tà</label>
                                                <input id="qta-invio-{{ $i }}" type="number" min="0" step="0.001" wire:model.lazy="righe.{{ $i }}.qta" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                                @error("righe.$i.qta")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                            </div>
                                            <div class="lg:col-span-2 space-y-2">
                                                <label class="text-sm font-semibold text-slate-700" for="lotto-invio-{{ $i }}">Lotto</label>
                                                <input id="lotto-invio-{{ $i }}" type="text" wire:model.lazy="righe.{{ $i }}.lotto" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                            </div>
                                            <div class="lg:col-span-3 space-y-2">
                                                <label class="text-sm font-semibold text-slate-700" for="componenti-invio-{{ $i }}">Componenti / lavorazioni</label>
                                                <input id="componenti-invio-{{ $i }}" type="text" wire:model.lazy="righe.{{ $i }}.componenti" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500" placeholder="Es. fodera, bottoni…">
                                                @error("righe.$i.componenti")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($step === 3)
                        <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                            <header class="space-y-1">
                                <h2 class="text-lg font-semibold text-slate-900">Riepilogo invio</h2>
                                <p class="text-sm text-slate-600">Conferma i dati prima di registrare l&#39;uscita verso il terzista.</p>
                            </header>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
                                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Terzista</h3>
                                    <p class="text-base font-semibold text-slate-900">{{ $riepilogo['terzista'] ?? '—' }}</p>
                                    <p class="text-sm text-slate-600">Magazzino: <span class="font-semibold text-slate-900">{{ $riepilogo['magazzino'] ?? '—' }}</span></p>
                                    @if($riepilogo['ubicazione'] ?? false)
                                        <p class="text-xs text-slate-500">Ubicazione: {{ $riepilogo['ubicazione'] }}</p>
                                    @endif
                                </div>
                                <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
                                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Programmazione</h3>
                                    <dl class="space-y-2 text-sm text-slate-700">
                                        <div class="flex items-center justify-between gap-4">
                                            <dt class="text-slate-500">Data invio</dt>
                                            <dd class="font-medium">{{ $invio['data_invio'] }}</dd>
                                        </div>
                                        <div class="flex items-center justify-between gap-4">
                                            <dt class="text-slate-500">Rientro previsto</dt>
                                            <dd class="font-medium">{{ $invio['data_rientro_prevista'] ?: '—' }}</dd>
                                        </div>
                                        <div class="flex items-center justify-between gap-4">
                                            <dt class="text-slate-500">Note</dt>
                                            <dd class="font-medium text-right">{{ $invio['note'] ?: '—' }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>

                            <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead class="bg-slate-50/70 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left">Articolo</th>
                                            <th scope="col" class="px-4 py-3 text-right">Q.tà</th>
                                            <th scope="col" class="px-4 py-3 text-left">Lotto</th>
                                            <th scope="col" class="px-4 py-3 text-left">Componenti</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white/80">
                                        @foreach($riepilogo['righe'] ?? [] as $r)
                                            <tr>
                                                <td class="px-4 py-3 font-medium text-slate-700">{{ $r['codice'] }} — {{ $r['descrizione'] }}</td>
                                                <td class="px-4 py-3 text-right font-semibold text-slate-900">{{ $r['qta'] }}</td>
                                                <td class="px-4 py-3 text-slate-600">{{ $r['lotto'] ?: '—' }}</td>
                                                <td class="px-4 py-3 text-slate-600">{{ $r['componenti'] ?: '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @endif
                @else
                    @if($step === 1)
                        <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                            <header class="space-y-1">
                                <h2 class="text-lg font-semibold text-slate-900">Seleziona l&#39;ordine in rientro</h2>
                                <p class="text-sm text-slate-600">Scegli il documento di conto lavoro e il magazzino in cui registrare i pezzi rientrati.</p>
                            </header>

                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <div class="space-y-2">
                                    <label for="ordine-rientro" class="text-sm font-semibold text-slate-700">Ordine conto lavoro</label>
                                    <select id="ordine-rientro" wire:model="rientro.ordine_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                        <option value="">— seleziona —</option>
                                        @foreach($ordiniAperti as $ordine)
                                            <option value="{{ $ordine->id }}">#{{ $ordine->id }} — {{ $ordine->terzista?->ragione_sociale }} ({{ $ordine->stato }})</option>
                                        @endforeach
                                    </select>
                                    @error('rientro.ordine_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="magazzino-rientro" class="text-sm font-semibold text-slate-700">Magazzino di rientro</label>
                                    <select id="magazzino-rientro" wire:model="rientro.magazzino_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                        <option value="">— seleziona —</option>
                                        @foreach($magazzini as $m)
                                            <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
                                        @endforeach
                                    </select>
                                    @error('rientro.magazzino_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="ubicazione-rientro" class="text-sm font-semibold text-slate-700">Ubicazione</label>
                                    <select id="ubicazione-rientro" wire:model="rientro.ubicazione_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                        <option value="">— seleziona —</option>
                                        @foreach($ubicazioniRientro as $u)
                                            <option value="{{ $u->id }}">{{ $u->codice }} — {{ $u->descrizione }}</option>
                                        @endforeach
                                    </select>
                                    @error('rientro.ubicazione_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="note-rientro" class="text-sm font-semibold text-slate-700">Note interne</label>
                                <textarea id="note-rientro" rows="3" wire:model.lazy="rientro.note" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500" placeholder="Annotazioni sul rientro, difetti, ecc."></textarea>
                                @error('rientro.note')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                        </section>
                    @endif

                    @if($step === 2)
                        <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                            <header class="space-y-1">
                                <h2 class="text-lg font-semibold text-slate-900">Quantità da rientrare</h2>
                                <p class="text-sm text-slate-600">Inserisci le quantità rientrate e gli eventuali scarti per ogni riga.</p>
                            </header>

                            <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead class="bg-slate-50/70 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left">Articolo</th>
                                            <th scope="col" class="px-4 py-3 text-right">Inviato</th>
                                            <th scope="col" class="px-4 py-3 text-right">Disponibile</th>
                                            <th scope="col" class="px-4 py-3 text-right">Rientro</th>
                                            <th scope="col" class="px-4 py-3 text-right">Scarto</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white/80">
                                        @foreach($rientroRighe as $i => $riga)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="font-medium text-slate-700">{{ $riga['articolo'] }}</div>
                                                    @if($riga['lotto'])
                                                        <div class="text-xs text-slate-500">Lotto: {{ $riga['lotto'] }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-right font-semibold text-slate-900">{{ $riga['qta_inviata'] }}</td>
                                                <td class="px-4 py-3 text-right font-semibold text-slate-900">{{ $riga['disponibile'] }}</td>
                                                <td class="px-4 py-3 text-right">
                                                    <input type="number" min="0" max="{{ $riga['disponibile'] }}" step="0.001" wire:model.lazy="rientroRighe.{{ $i }}.qta_rientro" class="w-28 rounded-2xl border-slate-200 bg-white/90 p-2 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                                    @error("rientroRighe.$i.qta_rientro")<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    <input type="number" min="0" step="0.001" wire:model.lazy="rientroRighe.{{ $i }}.scarto" class="w-24 rounded-2xl border-slate-200 bg-white/90 p-2 text-sm shadow-sm focus:border-violet-500 focus:ring-violet-500">
                                                    @error("rientroRighe.$i.scarto")<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @endif

                    @if($step === 3)
                        <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                            <header class="space-y-1">
                                <h2 class="text-lg font-semibold text-slate-900">Riepilogo rientro</h2>
                                <p class="text-sm text-slate-600">Rivedi l&#39;ordine e le quantità che stai registrando.</p>
                            </header>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
                                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Ordine</h3>
                                    <p class="text-base font-semibold text-slate-900">#{{ $riepilogo['ordine'] ?? '—' }}</p>
                                    <p class="text-sm text-slate-600">Terzista: <span class="font-semibold text-slate-900">{{ $riepilogo['terzista'] ?? '—' }}</span></p>
                                </div>
                                <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
                                    <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Magazzino</h3>
                                    <p class="text-base font-semibold text-slate-900">{{ $riepilogo['magazzino'] ?? '—' }}</p>
                                    @if($riepilogo['ubicazione'] ?? false)
                                        <p class="text-xs text-slate-500">Ubicazione: {{ $riepilogo['ubicazione'] }}</p>
                                    @endif
                                    <p class="mt-2 text-sm text-slate-600">Note: <span class="font-semibold text-slate-900">{{ $rientro['note'] ?: '—' }}</span></p>
                                </div>
                            </div>

                            <div class="overflow-hidden rounded-2xl border border-slate-200/70">
                                <table class="min-w-full divide-y divide-slate-200 text-sm">
                                    <thead class="bg-slate-50/70 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left">Articolo</th>
                                            <th scope="col" class="px-4 py-3 text-right">Rientro</th>
                                            <th scope="col" class="px-4 py-3 text-right">Scarto</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white/80">
                                        @foreach($rientroRighe as $r)
                                            @if((float)($r['qta_rientro'] ?? 0) > 0 || (float)($r['scarto'] ?? 0) > 0)
                                                <tr>
                                                    <td class="px-4 py-3 font-medium text-slate-700">{{ $r['articolo'] }}</td>
                                                    <td class="px-4 py-3 text-right font-semibold text-slate-900">{{ $r['qta_rientro'] }}</td>
                                                    <td class="px-4 py-3 text-right font-semibold text-slate-900">{{ $r['scarto'] }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    @endif
                @endif

                @if ($errors->has('general'))
                    <div class="rounded-2xl border border-rose-200 bg-rose-50/80 p-4 text-sm text-rose-700 shadow-sm">
                        {{ $errors->first('general') }}
                    </div>
                @endif
            </div>

            <footer class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
                <button type="button" class="btn-secondary rounded-2xl px-5 py-2 text-sm font-semibold" wire:click="back" @disabled($step===1)>
                    Indietro
                </button>

                <div class="flex items-center gap-3">
                    @if($step < 3)
                        <button type="button" class="btn-primary rounded-2xl px-5 py-2 text-sm font-semibold" wire:click="next" wire:target="next" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="next">Avanti</span>
                            <span wire:loading wire:target="next">Attendere…</span>
                        </button>
                    @else
                        <button type="button" class="btn-primary rounded-2xl px-5 py-2 text-sm font-semibold" wire:click="conferma" wire:target="conferma" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="conferma">Conferma</span>
                            <span wire:loading wire:target="conferma">Salvataggio…</span>
                        </button>
                    @endif
                </div>
            </footer>

            @if (session('ok'))
                <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 text-sm text-emerald-700 shadow-sm">
                    {{ session('ok') }}
                </div>
            @endif
        </div>
    </div>
</div>

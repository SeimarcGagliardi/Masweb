@php
    $steps = [
        1 => ['label' => 'Dati generali', 'subtitle' => 'Magazzino e riferimenti'],
        2 => ['label' => 'Articoli', 'subtitle' => 'Quantità e lotti'],
        3 => ['label' => 'Riepilogo', 'subtitle' => 'Controlla e conferma'],
    ];
@endphp

<div class="wizard-shell min-h-screen bg-gradient-to-br from-rose-50 via-white to-sky-50 pb-10 pt-6">
    <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 lg:px-6">
        <section class="relative overflow-hidden rounded-3xl border border-white/60 bg-white/80 p-8 shadow-lg backdrop-blur">
            <div class="absolute -right-12 -top-16 hidden h-56 w-56 rotate-6 rounded-full bg-brand-100 blur-3xl md:block"></div>
            <img src="{{ asset('images/warehouse-racks.svg') }}" alt="Illustrazione del magazzino" class="absolute -right-6 bottom-0 w-48 opacity-80 md:-right-4 md:w-64 lg:w-72" aria-hidden="true">
            <div class="relative z-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">Movimenti • Carico</p>
                    <h1 class="text-3xl font-semibold text-slate-900">Accetta la merce in ingresso con pochi passaggi</h1>
                    <p class="max-w-2xl text-sm text-slate-600">Compila i dati principali, aggiungi gli articoli e verifica subito il riepilogo prima di confermare. L&#39;interfaccia è pensata anche per l&#39;uso da smartphone.</p>
                </div>
                <dl class="grid grid-cols-2 gap-4 rounded-2xl border border-slate-200/80 bg-white/70 p-4 text-sm text-slate-600 md:w-64">
                    <div>
                        <dt class="font-semibold text-slate-500">Passo corrente</dt>
                        <dd class="text-lg font-semibold text-slate-900">{{ $steps[$step]['label'] }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-500">Stato</dt>
                        <dd class="flex items-center gap-1 text-brand-600">
                            <span class="h-2 w-2 rounded-full bg-brand-500"></span>
                            Attivo
                        </dd>
                    </div>
                </dl>
            </div>
        </section>

        <div class="rounded-3xl border border-white/60 bg-white/90 p-6 shadow-xl backdrop-blur">
            <ol class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
                @foreach($steps as $index => $info)
                    <li class="relative flex flex-1 items-start gap-3 sm:flex-col sm:items-center sm:text-center">
                        <span
                            class="flex h-11 w-11 items-center justify-center rounded-2xl border text-sm font-semibold transition @if($step > $index) border-brand-600 bg-brand-600 text-white shadow-md @elseif($step === $index) border-brand-600 bg-brand-50 text-brand-700 shadow @else border-slate-200 bg-white text-slate-400 @endif">
                            {{ $index }}
                        </span>
                        <div class="flex-1 sm:flex sm:flex-col sm:items-center">
                            <p class="text-sm font-semibold text-slate-900">{{ $info['label'] }}</p>
                            <p class="text-xs text-slate-500">{{ $info['subtitle'] }}</p>
                        </div>
                        @if($index < count($steps))
                            <span class="absolute left-5 top-5 hidden h-px w-full translate-y-1/2 bg-gradient-to-r from-slate-200 via-slate-100 to-slate-200 sm:block"></span>
                        @endif
                    </li>
                @endforeach
            </ol>

            <div class="mt-8 space-y-6">
                @if($step === 1)
                    <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                        <header class="space-y-1">
                            <h2 class="text-lg font-semibold text-slate-900">Dati di carico</h2>
                            <p class="text-sm text-slate-600">Seleziona il magazzino di destinazione e completa i riferimenti utili alla movimentazione.</p>
                        </header>

                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700" for="magazzino">Magazzino di destinazione</label>
                                <select id="magazzino" wire:model="contesto.magazzino_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                    <option value="">— seleziona —</option>
                                    @foreach($magazzini as $m)
                                        <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
                                    @endforeach
                                </select>
                                @error('contesto.magazzino_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700" for="ubicazione">Ubicazione</label>
                                <select id="ubicazione" wire:model="contesto.ubicazione_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                    <option value="">— seleziona —</option>
                                    @foreach($ubicazioni as $u)
                                        <option value="{{ $u->id }}">{{ $u->codice }} — {{ $u->descrizione }}</option>
                                    @endforeach
                                </select>
                                @error('contesto.ubicazione_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                @if($ubicazioni->isEmpty() && $contesto['magazzino_id'])
                                    <p class="text-xs text-slate-500">Il magazzino selezionato non ha ubicazioni attive: il carico verrà assegnato al magazzino.</p>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700" for="commessa">Commessa</label>
                                <input id="commessa" type="text" wire:model.lazy="contesto.commessa" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="Es. PRJ-2025">
                                @error('contesto.commessa')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700" for="riferimento">Riferimento documento</label>
                                <input id="riferimento" type="text" wire:model.lazy="contesto.riferimento" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="DDT, ordine…">
                                @error('contesto.riferimento')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700" for="bagno">Bagno</label>
                                <input id="bagno" type="text" wire:model.lazy="contesto.bagno" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                @error('contesto.bagno')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700" for="linea">Linea</label>
                                <input id="linea" type="text" wire:model.lazy="contesto.linea" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                @error('contesto.linea')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700" for="note">Note operative</label>
                            <textarea id="note" rows="3" wire:model.lazy="contesto.note" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500" placeholder="Indicazioni per il magazzino…"></textarea>
                            @error('contesto.note')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </section>
                @endif

                @if($step === 2)
                    <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <header>
                                <h2 class="text-lg font-semibold text-slate-900">Articoli in ingresso</h2>
                                <p class="text-sm text-slate-600">Inserisci ogni riga con articolo, quantità e lotto opzionale.</p>
                            </header>
                            <button type="button" wire:click="addRiga" class="btn-secondary rounded-2xl px-4 py-2 text-sm font-semibold text-brand-600 shadow-sm hover:bg-brand-50 hover:text-brand-700">
                                + Aggiungi riga
                            </button>
                        </div>

                        <div class="space-y-6">
                            @foreach($righe as $i => $riga)
                                <article wire:key="carico-riga-{{ $i }}" class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                                        <div class="md:col-span-6 space-y-2">
                                            <label class="text-sm font-semibold text-slate-700" for="articolo-{{ $i }}">Articolo</label>
                                            <select id="articolo-{{ $i }}" wire:model="righe.{{ $i }}.articolo_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                                <option value="">— seleziona —</option>
                                                @foreach($articoli as $a)
                                                    <option value="{{ $a->id }}">{{ $a->codice }} — {{ $a->descrizione }}</option>
                                                @endforeach
                                            </select>
                                            @error("righe.$i.articolo_id")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>

                                        <div class="md:col-span-3 space-y-2">
                                            <label class="text-sm font-semibold text-slate-700" for="qta-{{ $i }}">Q.tà</label>
                                            <input id="qta-{{ $i }}" type="number" min="0" step="0.001" wire:model.lazy="righe.{{ $i }}.qta" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                            @error("righe.$i.qta")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>

                                        <div class="md:col-span-2 space-y-2">
                                            <label class="text-sm font-semibold text-slate-700" for="lotto-{{ $i }}">Lotto</label>
                                            <input id="lotto-{{ $i }}" type="text" wire:model.lazy="righe.{{ $i }}.lotto" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                            @error("righe.$i.lotto")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>

                                        <div class="md:col-span-1 flex items-end">
                                            <button type="button" wire:click="removeRiga({{ $i }})" class="w-full rounded-2xl border border-rose-200 bg-rose-50/70 p-3 text-sm font-semibold text-rose-600 shadow-sm transition hover:bg-rose-100">
                                                🗑️
                                            </button>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($step === 3)
                    <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                        <header class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Riepilogo carico</h2>
                                <p class="text-sm text-slate-600">Controlla le informazioni prima di registrare il movimento.</p>
                            </div>
                            <button type="button" wire:click="back" class="btn-ghost text-sm font-semibold text-brand-600 hover:text-brand-700">
                                ↺ Modifica dati
                            </button>
                        </header>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
                                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Magazzino</h3>
                                <p class="text-base font-semibold text-slate-900">{{ $riepilogo['magazzino'] ?? '—' }}</p>
                                @if($riepilogo['ubicazione'] ?? false)
                                    <p class="text-xs text-slate-500">Ubicazione: {{ $riepilogo['ubicazione'] }}</p>
                                @endif
                            </div>
                            <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
                                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Riferimenti</h3>
                                <dl class="space-y-1 text-sm text-slate-700">
                                    <div class="flex items-center justify-between gap-4">
                                        <dt class="text-slate-500">Commessa</dt>
                                        <dd class="font-medium">{{ $contesto['commessa'] ?: '—' }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <dt class="text-slate-500">Documento</dt>
                                        <dd class="font-medium">{{ $contesto['riferimento'] ?: '—' }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <dt class="text-slate-500">Bagno</dt>
                                        <dd class="font-medium">{{ $contesto['bagno'] ?: '—' }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <dt class="text-slate-500">Linea</dt>
                                        <dd class="font-medium">{{ $contesto['linea'] ?: '—' }}</dd>
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
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white/80">
                                    @foreach($riepilogo['righe'] ?? [] as $r)
                                        <tr>
                                            <td class="px-4 py-3 font-medium text-slate-700">{{ $r['codice'] }} — {{ $r['descrizione'] }}</td>
                                            <td class="px-4 py-3 text-right font-semibold text-slate-900">{{ $r['qta'] }}</td>
                                            <td class="px-4 py-3 text-slate-600">{{ $r['lotto'] ?: '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
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
                            <span wire:loading.remove wire:target="conferma">Conferma carico</span>
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

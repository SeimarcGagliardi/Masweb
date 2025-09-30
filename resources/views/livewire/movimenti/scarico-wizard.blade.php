@php
    $steps = [
        1 => ['label' => 'Contesto', 'subtitle' => 'Operazione e riferimenti'],
        2 => ['label' => 'Articoli', 'subtitle' => 'Dettaglio prelievo/reso'],
        3 => ['label' => 'Riepilogo', 'subtitle' => 'Verifica finale'],
    ];
@endphp

<div class="wizard-shell min-h-screen bg-gradient-to-br from-amber-50 via-white to-slate-100 pb-10 pt-6">
    <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 lg:px-6">
        <section class="relative overflow-hidden rounded-3xl border border-white/60 bg-white/85 p-8 shadow-lg backdrop-blur">
            <div class="absolute -left-10 top-0 h-52 w-52 -translate-y-8 rotate-12 rounded-full bg-amber-100 blur-3xl"></div>
            <img src="{{ asset('images/knit-fibers.svg') }}" alt="Illustrazione filati" class="absolute -right-10 bottom-0 w-52 opacity-80 md:w-64 lg:w-72" aria-hidden="true">
            <div class="relative z-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-wider text-amber-600">Movimenti • Prelievo / Reso</p>
                    <h1 class="text-3xl font-semibold text-slate-900">Coordina uscite e rientri di magazzino in modo chiaro</h1>
                    <p class="max-w-2xl text-sm text-slate-600">Scegli l&#39;operazione, indica le quantità e registra eventuali resi in pochi tap. Tutto ottimizzato per lavorare da tablet o smartphone.</p>
                </div>
                <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 text-sm text-slate-600 shadow-sm md:w-72">
                    <p class="font-semibold text-slate-500">Operatore attivo</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $contesto['operatore'] }}</p>
                    <p class="mt-2 text-xs uppercase tracking-wide text-amber-600">Step {{ $step }} di {{ count($steps) }}</p>
                </div>
            </div>
        </section>

        <div class="rounded-3xl border border-white/60 bg-white/90 p-6 shadow-xl backdrop-blur">
            <ol class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
                @foreach($steps as $index => $info)
                    <li class="relative flex flex-1 items-start gap-3 sm:flex-col sm:items-center sm:text-center">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl border text-sm font-semibold transition @if($step > $index) border-amber-600 bg-amber-500 text-white shadow-md @elseif($step === $index) border-amber-500 bg-amber-50 text-amber-700 shadow @else border-slate-200 bg-white text-slate-400 @endif">
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
                            <h2 class="text-lg font-semibold text-slate-900">Imposta il contesto</h2>
                            <p class="text-sm text-slate-600">Definisci se si tratta di un prelievo o di un reso e indica magazzino e riferimenti utili.</p>
                        </header>

                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <span class="text-sm font-semibold text-slate-700">Operazione</span>
                            <div class="flex gap-3">
                                <label class="flex items-center gap-2 rounded-2xl border @if($contesto['tipo']==='prelievo') border-amber-500 bg-amber-50 text-amber-700 shadow-sm @else border-slate-200 bg-white text-slate-600 @endif px-4 py-2 text-sm font-semibold transition">
                                    <input type="radio" wire:model="contesto.tipo" value="prelievo" class="hidden">
                                    <span>Prelievo</span>
                                </label>
                                <label class="flex items-center gap-2 rounded-2xl border @if($contesto['tipo']==='reso') border-amber-500 bg-amber-50 text-amber-700 shadow-sm @else border-slate-200 bg-white text-slate-600 @endif px-4 py-2 text-sm font-semibold transition">
                                    <input type="radio" wire:model="contesto.tipo" value="reso" class="hidden">
                                    <span>Reso</span>
                                </label>
                            </div>
                        </div>
                        @error('contesto.tipo')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror

                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <div class="space-y-2">
                                <label for="magazzino" class="text-sm font-semibold text-slate-700">Magazzino</label>
                                <select id="magazzino" wire:model="contesto.magazzino_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                    <option value="">— seleziona —</option>
                                    @foreach($magazzini as $m)
                                        <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
                                    @endforeach
                                </select>
                                @error('contesto.magazzino_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label for="ubicazione" class="text-sm font-semibold text-slate-700">Ubicazione</label>
                                <select id="ubicazione" wire:model="contesto.ubicazione_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                    <option value="">— seleziona —</option>
                                    @foreach($ubicazioni as $u)
                                        <option value="{{ $u->id }}">{{ $u->codice }} — {{ $u->descrizione }}</option>
                                    @endforeach
                                </select>
                                @error('contesto.ubicazione_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                @if($ubicazioni->isEmpty() && $contesto['magazzino_id'])
                                    <p class="text-xs text-slate-500">Non ci sono ubicazioni attive per il magazzino selezionato.</p>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <label for="operatore" class="text-sm font-semibold text-slate-700">Operatore</label>
                                <input id="operatore" type="text" wire:model.lazy="contesto.operatore" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                @error('contesto.operatore')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label for="commessa" class="text-sm font-semibold text-slate-700">Commessa</label>
                                <input id="commessa" type="text" wire:model.lazy="contesto.commessa" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                @error('contesto.commessa')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2 lg:col-span-2">
                                <label for="destinatario" class="text-sm font-semibold text-slate-700">Destinatario / Reparto</label>
                                <input id="destinatario" type="text" wire:model.lazy="contesto.destinatario" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500" placeholder="Es. sartoria, terzista…">
                                @error('contesto.destinatario')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="note" class="text-sm font-semibold text-slate-700">Note</label>
                            <textarea id="note" rows="3" wire:model.lazy="contesto.note" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500" placeholder="Dettagli aggiuntivi"></textarea>
                            @error('contesto.note')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </section>
                @endif

                @if($step === 2)
                    <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <header>
                                <h2 class="text-lg font-semibold text-slate-900">Articoli movimentati</h2>
                                <p class="text-sm text-slate-600">Specifica gli articoli interessati, la quantità e l&#39;eventuale lotto.</p>
                            </header>
                            <button type="button" wire:click="addRiga" class="btn-secondary rounded-2xl px-4 py-2 text-sm font-semibold text-amber-600 shadow-sm hover:bg-amber-50 hover:text-amber-700">
                                + Aggiungi riga
                            </button>
                        </div>

                        <div class="space-y-6">
                            @foreach($righe as $i => $riga)
                                <article wire:key="scarico-riga-{{ $i }}" class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                                        <div class="md:col-span-6 space-y-2">
                                            <label class="text-sm font-semibold text-slate-700" for="articolo-{{ $i }}">Articolo</label>
                                            <select id="articolo-{{ $i }}" wire:model="righe.{{ $i }}.articolo_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                                <option value="">— seleziona —</option>
                                                @foreach($articoli as $a)
                                                    <option value="{{ $a->id }}">{{ $a->codice }} — {{ $a->descrizione }}</option>
                                                @endforeach
                                            </select>
                                            @error("righe.$i.articolo_id")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>
                                        <div class="md:col-span-3 space-y-2">
                                            <label class="text-sm font-semibold text-slate-700" for="qta-{{ $i }}">Q.tà</label>
                                            <input id="qta-{{ $i }}" type="number" min="0" step="0.001" wire:model.lazy="righe.{{ $i }}.qta" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                            @error("righe.$i.qta")<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>
                                        <div class="md:col-span-2 space-y-2">
                                            <label class="text-sm font-semibold text-slate-700" for="lotto-{{ $i }}">Lotto</label>
                                            <input id="lotto-{{ $i }}" type="text" wire:model.lazy="righe.{{ $i }}.lotto" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500">
                                        </div>
                                        <div class="md:col-span-1 flex items-end">
                                            <button type="button" wire:click="removeRiga({{ $i }})" class="w-full rounded-2xl border border-rose-200 bg-rose-50/80 p-3 text-sm font-semibold text-rose-600 shadow-sm transition hover:bg-rose-100">
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
                        <header class="space-y-1">
                            <h2 class="text-lg font-semibold text-slate-900">Riepilogo operazione</h2>
                            <p class="text-sm text-slate-600">Verifica tutti i dati prima di confermare l&#39;uscita o il rientro.</p>
                        </header>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
                                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Operazione</h3>
                                <p class="mt-1 inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-amber-700">
                                    {{ strtoupper($contesto['tipo']) }}
                                </p>
                                <p class="mt-3 text-sm text-slate-600">Magazzino: <span class="font-semibold text-slate-900">{{ $riepilogo['magazzino'] ?? '—' }}</span></p>
                                @if($riepilogo['ubicazione'] ?? false)
                                    <p class="text-xs text-slate-500">Ubicazione: {{ $riepilogo['ubicazione'] }}</p>
                                @endif
                            </div>
                            <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
                                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Dettagli</h3>
                                <dl class="space-y-2 text-sm text-slate-700">
                                    <div class="flex items-center justify-between gap-4">
                                        <dt class="text-slate-500">Operatore</dt>
                                        <dd class="font-medium">{{ $contesto['operatore'] }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <dt class="text-slate-500">Commessa</dt>
                                        <dd class="font-medium">{{ $contesto['commessa'] ?: '—' }}</dd>
                                    </div>
                                    <div class="flex items-center justify-between gap-4">
                                        <dt class="text-slate-500">Destinatario</dt>
                                        <dd class="font-medium">{{ $contesto['destinatario'] ?: '—' }}</dd>
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
                            <span wire:loading.remove wire:target="conferma">Conferma operazione</span>
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

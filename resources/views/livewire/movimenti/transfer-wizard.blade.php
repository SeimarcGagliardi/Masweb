@php
    $steps = [
        1 => ['label' => 'Origine', 'subtitle' => 'Magazzino di partenza'],
        2 => ['label' => 'Destinazione', 'subtitle' => 'Magazzino di arrivo'],
        3 => ['label' => 'Articoli', 'subtitle' => 'Quantità e lotti'],
        4 => ['label' => 'Riepilogo', 'subtitle' => 'Controlla e conferma'],
    ];
@endphp

<div class="wizard-shell min-h-screen bg-gradient-to-br from-slate-50 via-white to-brand-50 pb-10 pt-6">
    <div class="mx-auto flex max-w-6xl flex-col gap-6 px-4 lg:px-6">
        <section class="relative overflow-hidden rounded-3xl border border-white/60 bg-white/85 p-8 shadow-lg backdrop-blur">
            <div class="absolute -left-12 bottom-0 hidden h-56 w-56 rotate-12 rounded-full bg-brand-100 blur-3xl md:block"></div>
            <img src="{{ asset('images/loom-weave.svg') }}" alt="Illustrazione trasferimento" class="absolute -right-8 bottom-0 w-56 opacity-80 md:w-72" aria-hidden="true">
            <div class="relative z-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-wider text-brand-600">Movimenti • Trasferimento</p>
                    <h1 class="text-3xl font-semibold text-slate-900">Trasferisci materiale tra magazzini con un flusso guidato</h1>
                    <p class="max-w-2xl text-sm text-slate-600">Segui i quattro step per scegliere origine, destinazione e articoli. Il riepilogo finale ti permette di validare tutto prima del salvataggio.</p>
                </div>
                <div class="grid gap-3 rounded-2xl border border-slate-200/70 bg-white/70 p-4 text-sm text-slate-600 shadow-sm md:w-72">
                    <div>
                        <p class="font-semibold text-slate-500">Origine selezionata</p>
                        <p class="text-base font-semibold text-slate-900">
                            @php($origineMag = $magazzini->firstWhere('id', $origine['magazzino_id']))
                            {{ $origineMag?->descrizione ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-500">Destinazione</p>
                        <p class="text-base font-semibold text-slate-900">
                            @php($destMag = $magazzini->firstWhere('id', $destinazione['magazzino_id']))
                            {{ $destMag?->descrizione ?? '—' }}
                        </p>
                    </div>
                    <p class="text-xs uppercase tracking-wide text-brand-600">Step {{ $step }} di {{ count($steps) }}</p>
                </div>
            </div>
        </section>

        <div class="rounded-3xl border border-white/60 bg-white/90 p-6 shadow-xl backdrop-blur">
            <ol class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
                @foreach($steps as $index => $info)
                    <li class="relative flex flex-1 items-start gap-3 sm:flex-col sm:items-center sm:text-center">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl border text-sm font-semibold transition @if($step > $index) border-brand-600 bg-brand-600 text-white shadow-md @elseif($step === $index) border-brand-600 bg-brand-50 text-brand-700 shadow @else border-slate-200 bg-white text-slate-400 @endif">
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
                            <h2 class="text-lg font-semibold text-slate-900">Scegli il magazzino di origine</h2>
                            <p class="text-sm text-slate-600">Definisci da dove prelevare gli articoli e, se necessario, l&#39;ubicazione precisa.</p>
                        </header>

                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <div class="space-y-2">
                                <label for="origine-magazzino" class="text-sm font-semibold text-slate-700">Magazzino di origine</label>
                                <select id="origine-magazzino" wire:model="origine.magazzino_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                    <option value="">— seleziona —</option>
                                    @foreach($magazzini as $m)
                                        <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
                                    @endforeach
                                </select>
                                @error('origine.magazzino_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label for="origine-ubicazione" class="text-sm font-semibold text-slate-700">Ubicazione di origine</label>
                                <select id="origine-ubicazione" wire:model="origine.ubicazione_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                    <option value="">— seleziona —</option>
                                    @foreach($origineUbicazioni as $u)
                                        <option value="{{ $u->id }}">{{ $u->codice }} — {{ $u->descrizione }}</option>
                                    @endforeach
                                </select>
                                @error('origine.ubicazione_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                @if($origineUbicazioni->isEmpty() && $origine['magazzino_id'])
                                    <p class="text-xs text-slate-500">Questo magazzino non ha ubicazioni attive: il trasferimento partirà dal magazzino generale.</p>
                                @endif
                            </div>
                        </div>
                    </section>
                @endif

                @if($step === 2)
                    <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                        <header class="space-y-1">
                            <h2 class="text-lg font-semibold text-slate-900">Imposta la destinazione</h2>
                            <p class="text-sm text-slate-600">Scegli il magazzino di arrivo e, se necessario, l&#39;ubicazione in cui stoccare la merce.</p>
                        </header>

                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            <div class="space-y-2">
                                <label for="dest-magazzino" class="text-sm font-semibold text-slate-700">Magazzino di destinazione</label>
                                <select id="dest-magazzino" wire:model="destinazione.magazzino_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                    <option value="">— seleziona —</option>
                                    @foreach($magazzini as $m)
                                        <option value="{{ $m->id }}">{{ $m->descrizione }} ({{ $m->codice }})</option>
                                    @endforeach
                                </select>
                                @error('destinazione.magazzino_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-2">
                                <label for="dest-ubicazione" class="text-sm font-semibold text-slate-700">Ubicazione di destinazione</label>
                                <select id="dest-ubicazione" wire:model="destinazione.ubicazione_id" class="w-full rounded-2xl border-slate-200 bg-white/90 p-3 text-sm shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                    <option value="">— seleziona —</option>
                                    @foreach($destinazioneUbicazioni as $u)
                                        <option value="{{ $u->id }}">{{ $u->codice }} — {{ $u->descrizione }}</option>
                                    @endforeach
                                </select>
                                @error('destinazione.ubicazione_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
                                @if($destinazioneUbicazioni->isEmpty() && $destinazione['magazzino_id'])
                                    <p class="text-xs text-slate-500">Non sono presenti ubicazioni attive per il magazzino selezionato.</p>
                                @endif
                            </div>
                        </div>
                    </section>
                @endif

                @if($step === 3)
                    <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <header>
                                <h2 class="text-lg font-semibold text-slate-900">Articoli da trasferire</h2>
                                <p class="text-sm text-slate-600">Inserisci tutte le righe con articolo, quantità e lotto opzionale.</p>
                            </header>
                            <button type="button" wire:click="addRiga" class="btn-secondary rounded-2xl px-4 py-2 text-sm font-semibold text-brand-600 shadow-sm hover:bg-brand-50 hover:text-brand-700">
                                + Aggiungi riga
                            </button>
                        </div>

                        <div class="space-y-6">
                            @foreach($righe as $i => $riga)
                                <article wire:key="transfer-riga-{{ $i }}" class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
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

                @if($step === 4)
                    <section class="space-y-6 rounded-3xl border border-slate-200/60 bg-white/80 p-6 shadow-sm backdrop-blur-sm">
                        <header class="space-y-1">
                            <h2 class="text-lg font-semibold text-slate-900">Riepilogo trasferimento</h2>
                            <p class="text-sm text-slate-600">Controlla attentamente i dati prima di confermare il movimento.</p>
                        </header>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
                                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Origine</h3>
                                <p class="text-base font-semibold text-slate-900">{{ $origineMag?->descrizione ?? '—' }}</p>
                                @if($riepilogo['origine']['ubicazione_label'] ?? false)
                                    <p class="text-xs text-slate-500">Ubicazione: {{ $riepilogo['origine']['ubicazione_label'] }}</p>
                                @endif
                            </div>
                            <div class="rounded-2xl border border-slate-200/70 bg-white/70 p-4 shadow-sm">
                                <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Destinazione</h3>
                                <p class="text-base font-semibold text-slate-900">{{ $destMag?->descrizione ?? '—' }}</p>
                                @if($riepilogo['destinazione']['ubicazione_label'] ?? false)
                                    <p class="text-xs text-slate-500">Ubicazione: {{ $riepilogo['destinazione']['ubicazione_label'] }}</p>
                                @endif
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
                                            <td class="px-4 py-3 font-medium text-slate-700">{{ $r['codice'] }} — {{ $r['descr'] }}</td>
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
                    @if($step < 4)
                        <button type="button" class="btn-primary rounded-2xl px-5 py-2 text-sm font-semibold" wire:click="next" wire:target="next" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="next">Avanti</span>
                            <span wire:loading wire:target="next">Attendere…</span>
                        </button>
                    @else
                        <button type="button" class="btn-primary rounded-2xl px-5 py-2 text-sm font-semibold" wire:click="conferma" wire:target="conferma" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="conferma">Conferma trasferimento</span>
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

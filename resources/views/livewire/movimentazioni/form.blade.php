<div class="space-y-6">
    {{-- STEP 1: selezione tipo movimento --}}
    @if($step === 1)
<div class="flex flex-wrap gap-4 justify-center">
    @foreach($tipi as $tipo)
        <button
            wire:click="selezionaTipoMovimento({{ $tipo->id }})"
            class="flex items-center justify-center text-center rounded-xl p-4 text-white shadow-md hover:shadow-xl hover:scale-105 transition-all duration-150"
            style="background-color: {{ $tipo->colore ?? '#4F46E5' }};
                   flex: 1 1 calc(50% - 1rem); /* default: 2 per riga */
                   max-width: calc(50% - 1rem);"
        >
            <span class="text-[4vw] sm:text-[2.2vw] md:text-[1.4vw] lg:text-[1vw] xl:text-base font-bold leading-snug break-words">
                {{ $tipo->descrizione }}
            </span>
        </button>
    @endforeach
</div>
@endif


    {{-- STEP 2: form dinamico --}}
    @if($step === 2)
        <div class="card bg-white shadow p-6 space-y-4">
            <h2 class="text-lg font-bold mb-4">Dati movimentazione</h2>

            {{-- Articolo --}}
            <div>
                <label class="label">Articolo</label>
                <select wire:model="articolo_id" class="select select-bordered w-full">
                    <option value="">-- Seleziona --</option>
                    @foreach($articoli as $a)
                        <option value="{{ $a->id }}">{{ $a->codice }} - {{ $a->descrizione }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Magazzino --}}
            <div>
                <label class="label">Magazzino</label>
                <select wire:model="magazzino_id" class="select select-bordered w-full">
                    <option value="">-- Seleziona --</option>
                    @foreach($magazzini as $m)
                        <option value="{{ $m->id }}">{{ $m->descrizione }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Quantità e UDM --}}
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="label">Quantità</label>
                    <input type="number" wire:model="quantita" class="input input-bordered w-full" step="0.001" min="0" />
                </div>
                <div class="w-24">
                    <label class="label">UDM</label>
                    <input type="text" wire:model="udm" class="input input-bordered w-full" />
                </div>
            </div>

            {{-- Ubicazione e bagno --}}
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="label">Ubicazione</label>
                    <input type="text" wire:model="ubicazione" class="input input-bordered w-full" />
                </div>
                <div class="w-1/3">
                    <label class="label">Bagno</label>
                    <input type="text" wire:model="bagno" class="input input-bordered w-full" />
                </div>
            </div>

            {{-- Colore e Taglia (mostrati solo se necessari) --}}
            @if($tipologia?->gestisce_colore)
                <div>
                    <label class="label">Colore</label>
                    <select wire:model="colore_id" class="select select-bordered w-full">
                        <option value="">-- Seleziona --</option>
                        @foreach($colori as $c)
                            <option value="{{ $c->id }}">{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($tipologia?->gestisce_taglia)
                <div>
                    <label class="label">Taglia</label>
                    <select wire:model="taglia_id" class="select select-bordered w-full">
                        <option value="">-- Seleziona --</option>
                        @foreach($taglie as $t)
                            <option value="{{ $t->id }}">{{ $t->nome }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Modello --}}
            <div>
                <label class="label">Riferimento modello</label>
                <input type="text" wire:model="riferimento_modello" class="input input-bordered w-full" />
            </div>

            {{-- Data --}}
            <div>
                <label class="label">Data</label>
                <input type="date" wire:model="data_movimento" class="input input-bordered w-full" />
            </div>

            {{-- Note --}}
            <div>
                <label class="label">Note</label>
                <textarea wire:model="note" class="textarea textarea-bordered w-full"></textarea>
            </div>

            {{-- Operatore --}}
            <div>
                <label class="label">Operatore</label>
                <select wire:model="operatore_id" class="select select-bordered w-full">
                    <option value="">-- Seleziona --</option>
                    @foreach($operatori as $o)
                        <option value="{{ $o->id }}">{{ $o->nome }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Foto da smartphone --}}
            <div>
                <label class="label">Foto (da smartphone)</label>
                <input type="file" wire:model="foto_riferimento" accept="image/*" capture="environment" class="file-input file-input-bordered w-full" />
                @error('foto_riferimento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Bottone salva --}}
            <button wire:click="save" class="btn btn-primary w-full">Salva movimentazione</button>
        </div>
    @endif
</div>

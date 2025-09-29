<?php

namespace App\Livewire\Movimenti;

use App\Models\{Articolo, Magazzino, Movimento, Ubicazione};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ScaricoWizard extends Component
{
    public int $step = 1;

    public array $contesto = [
        'tipo' => 'prelievo',
        'magazzino_id' => null,
        'ubicazione_id' => null,
        'commessa' => null,
        'operatore' => null,
        'destinatario' => null,
        'note' => null,
    ];

    /** @var array<int,array{articolo_id:int|null,qta:string,lotto:?string}> */
    public array $righe = [];

    public array $riepilogo = [];

    public function mount(): void
    {
        $this->righe = [
            ['articolo_id' => null, 'qta' => '', 'lotto' => null],
        ];
        $this->contesto['operatore'] = auth()->user()?->name;
    }

    public function updatedContestoMagazzinoId($value): void
    {
        $this->contesto['ubicazione_id'] = null;
    }

    public function addRiga(): void
    {
        $this->righe[] = ['articolo_id' => null, 'qta' => '', 'lotto' => null];
    }

    public function removeRiga(int $index): void
    {
        unset($this->righe[$index]);
        $this->righe = array_values($this->righe);
    }

    public function next(): void
    {
        $this->validateStep($this->step);
        if ($this->step === 2) {
            $this->buildRiepilogo();
        }
        $this->step++;
    }

    public function back(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    protected function validateStep(int $step): void
    {
        if ($step === 1) {
            $this->validate([
                'contesto.tipo' => 'required|in:prelievo,reso',
                'contesto.magazzino_id' => 'required|integer|exists:magazzini,id',
                'contesto.ubicazione_id' => ($this->richiedeUbicazione($this->contesto['magazzino_id']) ? 'required' : 'nullable').'|integer|exists:ubicazioni,id',
                'contesto.operatore' => 'required|string|max:120',
                'contesto.commessa' => 'nullable|string|max:120',
                'contesto.destinatario' => 'nullable|string|max:120',
                'contesto.note' => 'nullable|string|max:400',
            ]);
        }

        if ($step === 2) {
            $rules = [];
            foreach ($this->righe as $i => $riga) {
                $rules["righe.$i.articolo_id"] = 'required|integer|exists:articoli,id';
                $rules["righe.$i.qta"] = 'required|numeric|gt:0';
                $rules["righe.$i.lotto"] = 'nullable|string|max:50';
            }
            $this->validate($rules);
        }
    }

    protected function richiedeUbicazione($magazzinoId): bool
    {
        if (!$magazzinoId) {
            return false;
        }

        return Ubicazione::where('magazzino_id', $magazzinoId)
            ->where('attiva', true)
            ->exists();
    }

    protected function buildRiepilogo(): void
    {
        $magazzino = $this->contesto['magazzino_id']
            ? Magazzino::find($this->contesto['magazzino_id'])
            : null;
        $ubicazione = $this->contesto['ubicazione_id']
            ? Ubicazione::find($this->contesto['ubicazione_id'])
            : null;
        $articoli = Articolo::whereIn('id', collect($this->righe)->pluck('articolo_id')->filter()->all())
            ->get(['id', 'codice', 'descrizione'])
            ->keyBy('id');

        $this->riepilogo = [
            'magazzino' => $magazzino?->descrizione,
            'ubicazione' => $ubicazione?->descrizione,
            'contesto' => $this->contesto,
            'righe' => collect($this->righe)->map(function ($r) use ($articoli) {
                $articolo = $articoli->get((int) $r['articolo_id']);
                return [
                    'codice' => $articolo?->codice,
                    'descrizione' => $articolo?->descrizione,
                    'qta' => $r['qta'],
                    'lotto' => $r['lotto'],
                ];
            })->all(),
        ];
    }

    public function conferma(): void
    {
        $this->validateStep(1);
        $this->validateStep(2);

        DB::transaction(function () {
            $link = (string) Str::uuid();
            foreach ($this->righe as $riga) {
                $payload = [
                    'articolo_id' => $riga['articolo_id'],
                    'qta' => $riga['qta'],
                    'lotto' => $riga['lotto'] ?: null,
                    'utente_id' => auth()->id(),
                    'link_logico' => $link,
                    'note' => $this->buildNota(),
                    'riferimento' => $this->contesto['commessa'],
                ];

                if ($this->contesto['tipo'] === 'prelievo') {
                    $payload['tipo'] = 'SCARICO';
                    $payload['magazzino_orig'] = $this->contesto['magazzino_id'];
                    $payload['ubicazione_orig'] = $this->contesto['ubicazione_id'] ?: null;
                } else {
                    $payload['tipo'] = 'CARICO';
                    $payload['magazzino_dest'] = $this->contesto['magazzino_id'];
                    $payload['ubicazione_dest'] = $this->contesto['ubicazione_id'] ?: null;
                }

                Movimento::create($payload);
            }
        });

        session()->flash('ok', $this->contesto['tipo'] === 'prelievo' ? 'Prelievo registrato.' : 'Reso registrato.');
        $this->redirectRoute('movimenti.scarico', navigate: true);
    }

    protected function buildNota(): ?string
    {
        $chunks = collect([
            'Operatore: '.$this->contesto['operatore'],
            $this->contesto['destinatario'] ? 'Destinatario: '.$this->contesto['destinatario'] : null,
            $this->contesto['note'] ?: null,
        ])->filter();

        return $chunks->isEmpty() ? null : $chunks->implode(' | ');
    }

    public function render()
    {
        if ($this->step === 3 && empty($this->riepilogo)) {
            $this->buildRiepilogo();
        }

        return view('livewire.movimenti.scarico-wizard', [
            'magazzini' => Magazzino::where('attivo', true)->orderBy('descrizione')->get(),
            'articoli' => Articolo::where('attivo', true)->orderBy('descrizione')->limit(200)->get(),
            'ubicazioni' => $this->contesto['magazzino_id']
                ? Ubicazione::where('magazzino_id', $this->contesto['magazzino_id'])->where('attiva', true)->orderBy('descrizione')->get()
                : collect(),
        ]);
    }
}

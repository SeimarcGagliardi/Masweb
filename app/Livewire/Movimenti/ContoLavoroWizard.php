<?php

namespace App\Livewire\Movimenti;

use App\Models\{Articolo, Magazzino, Movimento, OrdineContoLavoro, RigaOCL, Terzista, Ubicazione};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Throwable;

#[Layout('layouts.app')]
class ContoLavoroWizard extends Component
{
    public int $step = 1;
    protected int $maxStep = 3;
    protected int $reviewStep = 2;
    public string $fase = 'invio';

    public array $invio = [
        'terzista_id' => null,
        'magazzino_id' => null,
        'ubicazione_id' => null,
        'data_invio' => null,
        'data_rientro_prevista' => null,
        'note' => null,
    ];

    public array $rientro = [
        'ordine_id' => null,
        'magazzino_id' => null,
        'ubicazione_id' => null,
        'note' => null,
    ];

    /** @var array<int,array{articolo_id:int|null,qta:string,lotto:?string,componenti:?string}> */
    public array $righe = [];

    /** @var array<int,array{id:int,articolo:string,qta_inviata:string,disponibile:string,qta_rientro:string,scarto:string,lotto:?string}> */
    public array $rientroRighe = [];

    public array $riepilogo = [];

    public function mount(): void
    {
        $this->invio['data_invio'] = Carbon::today()->toDateString();
        $this->righe = [
            ['articolo_id' => null, 'qta' => '', 'lotto' => null, 'componenti' => null],
        ];
    }

    public function switchFase(string $fase): void
    {
        if (!in_array($fase, ['invio', 'rientro'], true)) {
            return;
        }
        $this->fase = $fase;
        $this->step = 1;
        $this->riepilogo = [];
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatedInvioMagazzinoId($value): void
    {
        $this->invio['ubicazione_id'] = null;
    }

    public function updatedRientroMagazzinoId($value): void
    {
        $this->rientro['ubicazione_id'] = null;
    }

    public function updatedRientroOrdineId($value): void
    {
        $this->populateRientroRighe();
    }

    public function addRiga(): void
    {
        $this->righe[] = ['articolo_id' => null, 'qta' => '', 'lotto' => null, 'componenti' => null];
    }

    public function removeRiga(int $index): void
    {
        unset($this->righe[$index]);
        $this->righe = array_values($this->righe);
    }

    public function next(): void
    {
        if ($this->step >= $this->maxStep) {
            return;
        }

        try {
            $this->validateStep($this->step);

            if ($this->step === $this->reviewStep) {
                $this->fase === 'invio'
                    ? $this->buildRiepilogoInvio()
                    : $this->buildRiepilogoRientro();
            }

            $this->step = min($this->step + 1, $this->maxStep);
            $this->resetErrorBag();
        } catch (ValidationException $exception) {
            $this->setErrorBag($exception->validator->getMessageBag());
        } catch (Throwable $exception) {
            report($exception);
            $this->addError('general', 'Si è verificato un errore inatteso. Riprova oppure contatta il supporto.');
        }
    }

    public function back(): void
    {
        if ($this->step <= 1) {
            return;
        }

        $this->step--;
        $this->resetErrorBag();
    }

    protected function validateStep(int $step): void
    {
        if ($this->fase === 'invio') {
            $this->validateInvioStep($step);
        } else {
            $this->validateRientroStep($step);
        }
    }

    protected function validateInvioStep(int $step): void
    {
        if ($step === 1) {
            $this->validate([
                'invio.terzista_id' => 'required|integer|exists:terzisti,id',
                'invio.magazzino_id' => 'required|integer|exists:magazzini,id',
                'invio.ubicazione_id' => ($this->richiedeUbicazione($this->invio['magazzino_id']) ? 'required' : 'nullable').'|integer|exists:ubicazioni,id',
                'invio.data_invio' => 'required|date',
                'invio.data_rientro_prevista' => 'nullable|date|after_or_equal:invio.data_invio',
                'invio.note' => 'nullable|string|max:600',
            ]);
        }

        if ($step === 2) {
            $rules = [];
            foreach ($this->righe as $i => $riga) {
                $rules["righe.$i.articolo_id"] = 'required|integer|exists:articoli,id';
                $rules["righe.$i.qta"] = 'required|numeric|gt:0';
                $rules["righe.$i.lotto"] = 'nullable|string|max:50';
                $rules["righe.$i.componenti"] = 'nullable|string|max:500';
            }
            $this->validate($rules);
        }
    }

    protected function validateRientroStep(int $step): void
    {
        if ($step === 1) {
            $this->validate([
                'rientro.ordine_id' => 'required|integer|exists:ordine_conto_lavoro,id',
                'rientro.magazzino_id' => 'required|integer|exists:magazzini,id',
                'rientro.ubicazione_id' => ($this->richiedeUbicazione($this->rientro['magazzino_id']) ? 'required' : 'nullable').'|integer|exists:ubicazioni,id',
                'rientro.note' => 'nullable|string|max:600',
            ]);
            $this->populateRientroRighe();
        }

        if ($step === 2) {
            $rules = [];
            foreach ($this->rientroRighe as $i => $riga) {
                $rules["rientroRighe.$i.qta_rientro"] = 'nullable|numeric|min:0|max:'.$riga['disponibile'];
                $rules["rientroRighe.$i.scarto"] = 'nullable|numeric|min:0';
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

    protected function buildRiepilogoInvio(): void
    {
        $magazzino = $this->invio['magazzino_id'] ? Magazzino::find($this->invio['magazzino_id']) : null;
        $ubicazione = $this->invio['ubicazione_id'] ? Ubicazione::find($this->invio['ubicazione_id']) : null;
        $terzista = $this->invio['terzista_id'] ? Terzista::find($this->invio['terzista_id']) : null;
        $articoli = Articolo::whereIn('id', collect($this->righe)->pluck('articolo_id')->filter()->all())
            ->get(['id', 'codice', 'descrizione'])
            ->keyBy('id');

        $this->riepilogo = [
            'tipo' => 'invio',
            'magazzino' => $magazzino?->descrizione,
            'ubicazione' => $ubicazione?->descrizione,
            'terzista' => $terzista?->ragione_sociale,
            'dati' => $this->invio,
            'righe' => collect($this->righe)->map(function ($r) use ($articoli) {
                $articolo = $articoli->get((int) $r['articolo_id']);
                return [
                    'codice' => $articolo?->codice,
                    'descrizione' => $articolo?->descrizione,
                    'qta' => $r['qta'],
                    'lotto' => $r['lotto'],
                    'componenti' => $r['componenti'],
                ];
            })->all(),
        ];
    }

    protected function buildRiepilogoRientro(): void
    {
        $magazzino = $this->rientro['magazzino_id'] ? Magazzino::find($this->rientro['magazzino_id']) : null;
        $ubicazione = $this->rientro['ubicazione_id'] ? Ubicazione::find($this->rientro['ubicazione_id']) : null;
        $ordine = $this->rientro['ordine_id']
            ? OrdineContoLavoro::with(['righe.articolo', 'terzista'])->find($this->rientro['ordine_id'])
            : null;

        $this->riepilogo = [
            'tipo' => 'rientro',
            'magazzino' => $magazzino?->descrizione,
            'ubicazione' => $ubicazione?->descrizione,
            'ordine' => $ordine?->id,
            'terzista' => $ordine?->terzista?->ragione_sociale,
            'righe' => $this->rientroRighe,
        ];
    }

    protected function populateRientroRighe(): void
    {
        if (!$this->rientro['ordine_id']) {
            $this->rientroRighe = [];
            return;
        }

        $ordine = OrdineContoLavoro::with(['righe.articolo'])->find($this->rientro['ordine_id']);
        if (!$ordine) {
            $this->rientroRighe = [];
            return;
        }

        $this->rientroRighe = $ordine->righe->map(function (RigaOCL $r) {
            $disponibile = max(0, (float) $r->qta - (float) $r->qta_rientrata);
            return [
                'id' => $r->id,
                'articolo' => $r->articolo?->codice.' — '.$r->articolo?->descrizione,
                'qta_inviata' => (string) $r->qta,
                'disponibile' => (string) $disponibile,
                'qta_rientro' => $disponibile > 0 ? (string) $disponibile : '0',
                'scarto' => '0',
                'lotto' => $r->lotto,
            ];
        })->all();
    }

    public function conferma(): void
    {
        try {
            if ($this->fase === 'invio') {
                $this->validateInvioStep(1);
                $this->validateInvioStep(2);
                $this->storeInvio();
                session()->flash('ok', 'Invio al terzista registrato.');
            } else {
                $this->validateRientroStep(1);
                $this->validateRientroStep(2);
                $this->storeRientro();
                session()->flash('ok', 'Rientro conto lavoro aggiornato.');
            }

            $this->redirectRoute('conto-lavoro.wizard', navigate: true);
        } catch (ValidationException $exception) {
            $this->setErrorBag($exception->validator->getMessageBag());
            $this->step = min($this->step, $this->reviewStep);
            $this->addError('general', 'Controlla i campi evidenziati e riprova.');
        } catch (Throwable $exception) {
            report($exception);
            $this->addError('general', 'Impossibile completare l\'operazione in questo momento. Riprova più tardi.');
        }
    }

    protected function storeInvio(): void
    {
        DB::transaction(function () {
            $ordine = OrdineContoLavoro::create([
                'terzista_id' => $this->invio['terzista_id'],
                'stato' => 'Inviato',
                'data_invio' => $this->invio['data_invio'],
                'data_rientro_prevista' => $this->invio['data_rientro_prevista'],
                'note' => $this->invio['note'],
            ]);

            foreach ($this->righe as $riga) {
                $rigaModel = $ordine->righe()->create([
                    'articolo_id' => $riga['articolo_id'],
                    'qta' => $riga['qta'],
                    'lotto' => $riga['lotto'],
                ]);

                Movimento::create([
                    'tipo' => 'USC_CL',
                    'articolo_id' => $riga['articolo_id'],
                    'qta' => $riga['qta'],
                    'magazzino_orig' => $this->invio['magazzino_id'],
                    'ubicazione_orig' => $this->invio['ubicazione_id'] ?: null,
                    'lotto' => $riga['lotto'] ?: null,
                    'utente_id' => auth()->id(),
                    'link_logico' => (string) Str::uuid(),
                    'note' => $this->noteInvio($rigaModel, $riga['componenti'] ?? null),
                ]);
            }
        });
    }

    protected function noteInvio(RigaOCL $riga, ?string $componenti): string
    {
        $chunks = collect([
            'Invio conto lavoro #'.$riga->ordine_id,
            $componenti ? 'Componenti: '.$componenti : null,
            $this->invio['note'] ?: null,
        ])->filter();

        return $chunks->implode(' | ');
    }

    protected function storeRientro(): void
    {
        DB::transaction(function () {
            $ordine = OrdineContoLavoro::with(['righe'])->findOrFail($this->rientro['ordine_id']);

            foreach ($this->rientroRighe as $rigaInput) {
                $qta = (float) ($rigaInput['qta_rientro'] ?? 0);
                $scarto = (float) ($rigaInput['scarto'] ?? 0);
                if ($qta <= 0 && $scarto <= 0) {
                    continue;
                }

                /** @var RigaOCL $riga */
                $riga = $ordine->righe->firstWhere('id', $rigaInput['id']);
                if (!$riga) {
                    continue;
                }

                $riga->qta_rientrata = $riga->qta_rientrata + $qta;
                $riga->scarto = $riga->scarto + $scarto;
                $riga->stato_riga = $riga->qta_rientrata >= $riga->qta ? 'Chiusa' : 'Parziale';
                $riga->save();

                if ($qta > 0) {
                    Movimento::create([
                        'tipo' => 'ENT_CL',
                        'articolo_id' => $riga->articolo_id,
                        'qta' => $qta,
                        'magazzino_dest' => $this->rientro['magazzino_id'],
                        'ubicazione_dest' => $this->rientro['ubicazione_id'] ?: null,
                        'lotto' => $riga->lotto,
                        'utente_id' => auth()->id(),
                        'link_logico' => (string) Str::uuid(),
                        'note' => $this->noteRientro($ordine, $riga, $scarto),
                    ]);
                }
            }

            $ordine->stato = $ordine->righe->every(fn ($r) => $r->stato_riga === 'Chiusa') ? 'Chiuso' : 'Parziale';
            $ordine->save();
        });
    }

    protected function noteRientro(OrdineContoLavoro $ordine, RigaOCL $riga, float $scarto): string
    {
        $chunks = collect([
            'Rientro conto lavoro #'.$ordine->id,
            $scarto > 0 ? 'Scarto: '.$scarto : null,
            $this->rientro['note'] ?: null,
        ])->filter();

        return $chunks->implode(' | ');
    }

    public function render()
    {
        if ($this->fase === 'invio' && $this->step === 3 && empty($this->riepilogo)) {
            $this->buildRiepilogoInvio();
        }
        if ($this->fase === 'rientro' && $this->step === 3 && empty($this->riepilogo)) {
            $this->buildRiepilogoRientro();
        }

        $magazzini = Magazzino::where('attivo', true)->orderBy('descrizione')->get();

        return view('livewire.movimenti.conto-lavoro-wizard', [
            'magazzini' => $magazzini,
            'articoli' => Articolo::where('attivo', true)->orderBy('descrizione')->limit(200)->get(),
            'terzisti' => Terzista::where('attivo', true)->orderBy('ragione_sociale')->get(),
            'ubicazioniInvio' => $this->invio['magazzino_id']
                ? Ubicazione::where('magazzino_id', $this->invio['magazzino_id'])->where('attiva', true)->orderBy('descrizione')->get()
                : collect(),
            'ubicazioniRientro' => $this->rientro['magazzino_id']
                ? Ubicazione::where('magazzino_id', $this->rientro['magazzino_id'])->where('attiva', true)->orderBy('descrizione')->get()
                : collect(),
            'ordiniAperti' => OrdineContoLavoro::with('terzista')
                ->whereIn('stato', ['Inviato', 'Parziale'])
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }
}

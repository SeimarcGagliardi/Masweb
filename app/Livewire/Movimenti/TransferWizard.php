<?php // app/Livewire/Movimenti/TransferWizard.php
namespace App\Livewire\Movimenti;

use App\Models\{Articolo,Magazzino,Movimento,Ubicazione};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


#[Layout('layouts.app')]
class TransferWizard extends Component
{
    public int $step = 1;

    public array $origine = ['magazzino_id'=>null,'ubicazione_id'=>null];
    public array $destinazione = ['magazzino_id'=>null,'ubicazione_id'=>null];

    /** @var array<int,array{articolo_id:int|null,qta:string,lotto:?string,descr?:string,codice?:string}> */
    public array $righe = [];

    public array $riepilogo = [];

    public function mount(): void {
        $this->righe = [
            ['articolo_id'=>null,'qta'=>'','lotto'=>null],
        ];
    }

    public function addRiga(): void { $this->righe[] = ['articolo_id'=>null,'qta'=>'','lotto'=>null]; }
    public function removeRiga($idx): void { unset($this->righe[$idx]); $this->righe = array_values($this->righe); }

    public function next(): void
    {
        try {
            $this->validateStep($this->step);

            if ($this->step === 3) {
                $this->buildRiepilogo();
            }

            $this->step = min($this->step + 1, $this->maxStep());
            $this->resetErrorBag();
        }
        catch (ValidationException $e) {
            $this->setErrorBag($e->validator->getMessageBag());
            $this->addError('general', 'Controlla i campi evidenziati e riprova.');
        }
        catch (\Throwable $e) {
            Log::error('Errore avanzamento wizard trasferimento', [
                'message' => $e->getMessage(),
                'step' => $this->step,
            ]);
            $this->addError('general', 'Avanzamento non riuscito, riprova a breve.');
        }
    }
    private function buildRiepilogo(): void
    {
        $ids = collect($this->righe)->pluck('articolo_id')->filter()->unique()->all();
        $articoli = Articolo::whereIn('id', $ids)
            ->get(['id','codice','descrizione'])
            ->keyBy('id');

        $origUbicazione = $this->origine['ubicazione_id']
            ? Ubicazione::find($this->origine['ubicazione_id'])
            : null;
        $destUbicazione = $this->destinazione['ubicazione_id']
            ? Ubicazione::find($this->destinazione['ubicazione_id'])
            : null;

        $this->riepilogo = [
            'origine'      => $this->origine + ['ubicazione_label' => $origUbicazione?->descrizione],
            'destinazione' => $this->destinazione + ['ubicazione_label' => $destUbicazione?->descrizione],
            'righe'        => collect($this->righe)->map(function ($r) use ($articoli) {
                $a = $articoli->get((int)$r['articolo_id']);
                return [
                    'codice' => $a?->codice,
                    'descr'  => $a?->descrizione,
                    'qta'    => $r['qta'],
                    'lotto'  => $r['lotto'] ?? null,
                ];
            })->all(),
        ];
    }
    public function back(): void
    {
        if ($this->step > 1) {
            $this->step--;
            $this->resetErrorBag('general');
        }
    }

    protected function maxStep(): int
    {
        return 4;
    }

    protected function validateStep(int $step): void {
        if ($step === 1) {
            $rules = [
                'origine.magazzino_id' => 'required|integer|exists:magazzini,id|different:destinazione.magazzino_id',
            ];
            $ubicRule = $this->magazzinoRichiedeUbicazione($this->origine['magazzino_id'])
                ? 'required|integer|exists:ubicazioni,id'
                : 'nullable|integer|exists:ubicazioni,id';
            $rules['origine.ubicazione_id'] = $ubicRule;
            $this->validate($rules);
        }
        if ($step === 2) {
            $rules = [
                'destinazione.magazzino_id' => 'required|integer|exists:magazzini,id',
            ];
            $ubicRule = $this->magazzinoRichiedeUbicazione($this->destinazione['magazzino_id'])
                ? 'required|integer|exists:ubicazioni,id'
                : 'nullable|integer|exists:ubicazioni,id';
            $rules['destinazione.ubicazione_id'] = $ubicRule;
            $this->validate($rules);
        }
        if ($step === 3) {
            $rules = [];
            foreach($this->righe as $i => $r){
                $rules["righe.$i.articolo_id"] = 'required|integer|exists:articoli,id';
                $rules["righe.$i.qta"] = 'required|numeric|gt:0';
            }
            $this->validate($rules);
        }
        if ($step === 4) {
            $this->buildRiepilogo();
        }
    }



    public function conferma(): void
    {
        try {
            // ✅ validazione dentro al try: se fallisce, la intercettiamo
            $data = $this->validate([
                'origine.magazzino_id'        => 'required|integer|exists:magazzini,id|different:destinazione.magazzino_id',
                'origine.ubicazione_id'       => ($this->magazzinoRichiedeUbicazione($this->origine['magazzino_id']) ? 'required' : 'nullable').'|integer|exists:ubicazioni,id',
                'destinazione.magazzino_id'   => 'required|integer|exists:magazzini,id',
                'destinazione.ubicazione_id'  => ($this->magazzinoRichiedeUbicazione($this->destinazione['magazzino_id']) ? 'required' : 'nullable').'|integer|exists:ubicazioni,id',
                'righe'                       => 'required|array|min:1',
                'righe.*.articolo_id'         => 'required|integer|exists:articoli,id',
                'righe.*.qta'                 => 'required|numeric|gt:0',
                'righe.*.lotto'               => 'nullable|string',
            ]);

            DB::transaction(function () use ($data) {
                $link = (string) Str::uuid();

                foreach ($data['righe'] as $r) {
                    // OUT
                    Movimento::create([
                        'tipo'            => 'TRASF',
                        'articolo_id'     => $r['articolo_id'],
                        'qta'             => $r['qta'],
                        'magazzino_orig'  => $data['origine']['magazzino_id'],
                        'ubicazione_orig' => $data['origine']['ubicazione_id'] ?? null,
                        'magazzino_dest'  => null,
                        'lotto'           => $r['lotto'] ?? null,
                        'utente_id'       => auth()->id(),
                        'link_logico'     => $link,
                        'note'            => 'Trasferimento OUT',
                    ]);
                    // IN
                    Movimento::create([
                        'tipo'            => 'TRASF',
                        'articolo_id'     => $r['articolo_id'],
                        'qta'             => $r['qta'],
                        'magazzino_orig'  => null,
                        'magazzino_dest'  => $data['destinazione']['magazzino_id'],
                        'ubicazione_dest' => $data['destinazione']['ubicazione_id'] ?? null,
                        'lotto'           => $r['lotto'] ?? null,
                        'utente_id'       => auth()->id(),
                        'link_logico'     => $link,
                        'note'            => 'Trasferimento IN',
                    ]);
                }
            });
    
            session()->flash('ok', 'Trasferimento effettuato correttamente.');
            $this->redirectRoute('movimenti.transfer', navigate: true);
        }
        catch (ValidationException $ve) {
            // ⬅️ Torna allo STEP 3 e mostra gli errori sui campi
            $this->step = 3;
            $this->setErrorBag($ve->validator->getMessageBag());
            $this->addError('general', 'Controlla i campi evidenziati e riprova.');
        }
        catch (\Throwable $e) {
            \Log::error('Errore trasferimento', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->addError('general', 'Ops, qualcosa è andato storto: '.$e->getMessage());
        }
    }
    

    public function render() {
        if ($this->step === 4 && empty($this->riepilogo)) {
            $this->buildRiepilogo();
        }
        return view('livewire.movimenti.transfer-wizard', [
            'magazzini'=> Magazzino::where('attivo',true)->orderBy('descrizione')->get(),
            'articoli' => Articolo::where('attivo',true)->orderBy('descrizione')->limit(200)->get(),
            'origineUbicazioni' => $this->origine['magazzino_id']
                ? Ubicazione::where('magazzino_id', $this->origine['magazzino_id'])->where('attiva', true)->orderBy('descrizione')->get()
                : collect(),
            'destinazioneUbicazioni' => $this->destinazione['magazzino_id']
                ? Ubicazione::where('magazzino_id', $this->destinazione['magazzino_id'])->where('attiva', true)->orderBy('descrizione')->get()
                : collect(),
        ]);
    }

    public function updatedOrigineMagazzinoId($value): void
    {
        $this->origine['ubicazione_id'] = null;
    }

    public function updatedDestinazioneMagazzinoId($value): void
    {
        $this->destinazione['ubicazione_id'] = null;
    }

    protected function magazzinoRichiedeUbicazione($magazzinoId): bool
    {
        if (!$magazzinoId) {
            return false;
        }

        return Ubicazione::where('magazzino_id', $magazzinoId)
            ->where('attiva', true)
            ->exists();
    }
}

<?php // app/Livewire/Movimenti/TransferWizard.php
namespace App\Livewire\Movimenti;

use App\Http\Requests\TransferStoreRequest;
use App\Models\{Articolo,Magazzino,Movimento};
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;

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
        $this->validateStep($this->step);
    
        // 👇 se sto uscendo dallo step 3, preparo il riepilogo per lo step 4
        if ($this->step === 3) {
            $this->buildRiepilogo();
        }
    
        $this->step++;
    }
    private function buildRiepilogo(): void
{
    $ids = collect($this->righe)->pluck('articolo_id')->filter()->unique()->all();
    $articoli = Articolo::whereIn('id', $ids)
        ->get(['id','codice','descrizione'])
        ->keyBy('id');

    $this->riepilogo = [
        'origine'      => $this->origine,
        'destinazione' => $this->destinazione,
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
    public function back(): void { if($this->step>1) $this->step--; }

    protected function validateStep(int $step): void {
        if ($step === 1) {
            $this->validate([
                'origine.magazzino_id' => 'required|integer|exists:magazzini,id|different:destinazione.magazzino_id',
            ]);
        }
        if ($step === 2) {
            $this->validate([
                'destinazione.magazzino_id' => 'required|integer|exists:magazzini,id',
            ]);
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
            // costruisci riepilogo
            $this->riepilogo = [
                'origine' => $this->origine,
                'destinazione' => $this->destinazione,
                'righe' => collect($this->righe)->map(function($r){
                    $a = Articolo::find($r['articolo_id']);
                    return $r + ['codice'=>$a?->codice,'descr'=>$a?->descrizione];
                })->all()
            ];
        }
    }

   
    
    public function conferma(): void
    {
        try {
            // ✅ validazione dentro al try: se fallisce, la intercettiamo
            $data = $this->validate([
                'origine.magazzino_id'        => 'required|integer|exists:magazzini,id|different:destinazione.magazzino_id',
                'destinazione.magazzino_id'   => 'required|integer|exists:magazzini,id',
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
        ]);
    }
}

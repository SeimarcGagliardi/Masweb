<?php
namespace App\Livewire\Movimentazioni;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\{
    Articolo,
    TipoMovimento,
    Magazzino,
    MovimentazioneMagazzino,
    UtentePersonale,
    ModelloCapo,
    Colore,
    Taglia
};
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    use WithFileUploads;

    public $step = 1;
    public $tipo_movimento_id, $tipologia_movimento;
    public $articolo_id, $magazzino_id, $quantita, $udm = 'KG', $ubicazione, $bagno;
    public $taglia_id, $colore_id, $riferimento_modello, $data_movimento, $note, $foto_riferimento, $operatore_id;
    public $tipologia;

    public function selezionaTipoMovimento($id)
    {
        $this->tipologia_movimento = TipoMovimento::find($id);
        $this->step = 2;
    }

    public function updatedArticoloId()
    {
        $articolo = Articolo::with('tipologia')->find($this->articolo_id);
        $this->tipologia = $articolo?->tipologia;
    }

    public function save()
    {
       
        $this->validate([
            'articolo_id' => 'required|exists:articoli,id',
            'magazzino_id' => 'required|exists:magazzini,id',
            'tipo_movimento_id' => 'required|exists:tipo_movimento,id',
            'quantita' => 'required|numeric|min:0.001',
            'udm' => 'required|string|max:10',
            'data_movimento' => 'required|date',
            'foto_riferimento' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () {
            $path = $this->foto_riferimento
                ? $this->foto_riferimento->store('foto_movimenti', 'public')
                : null;

            $mov = MovimentazioneMagazzino::create([
                'articolo_id' => $this->articolo_id,
                'magazzino_id' => $this->magazzino_id,
                'tipo_movimento_id' => $this->tipo_movimento_id,
                'quantita' => $this->quantita,
                'udm' => $this->udm,
                'ubicazione' => $this->ubicazione,
                'bagno' => $this->bagno,
                'taglia_id' => $this->taglia_id,
                'colore_id' => $this->colore_id,
                'riferimento_modello' => $this->riferimento_modello,
                'data_movimento' => $this->data_movimento,
                'note' => $this->note,
                'foto_riferimento' => $path,
                'operatore_id' => $this->operatore_id,
            ]);

            // Se il tipo movimento prevede riga fantasma...
            $tipo = TipoMovimento::find($this->tipo_movimento_id);
            if ($tipo->segno2 !== null && $tipo->magazzino2 !== null) {
                MovimentazioneMagazzino::create([
                    'articolo_id' => $this->articolo_id,
                    'magazzino_id' => $tipo->magazzino2,
                    'movimentazioni_magazzino_id_padre' => $mov->id,
                    'tipo_movimento_id' => $this->tipo_movimento_id,
                    'quantita' => $tipo->segno2 * abs($this->quantita),
                    'udm' => $this->udm,
                    'ubicazione' => $this->ubicazione,
                    'bagno' => $this->bagno,
                    'taglia_id' => $this->taglia_id,
                    'colore_id' => $this->colore_id,
                    'riferimento_modello' => $this->riferimento_modello,
                    'data_movimento' => $this->data_movimento,
                    'note' => '[GENERATO AUTOMATICAMENTE]',
                    'operatore_id' => $this->operatore_id,
                    'foto_riferimento' => null,
                ]);
            }
        });

        session()->flash('success', 'Movimentazione salvata correttamente.');
        $this->reset();
    }

    public function render()
    {
        
        return view('livewire.movimentazioni.form', [
            'articoli' => Articolo::all(),
            'tipi' => TipoMovimento::all(),
            'magazzini' => Magazzino::all(),
            'operatori' => UtentePersonale::all(),
            'modelli' => ModelloCapo::all(),
            'colori' => Colore::all(),
            'taglie' => Taglia::all(),
        ])->layout('layouts.app');
    }
}

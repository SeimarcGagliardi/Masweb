<?php

namespace App\Livewire\Movimentazioni;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MovimentazioneMagazzino;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $updatesQueryString = ['search'];

    public function render()
    {
        $items = MovimentazioneMagazzino::query()
                    ->when($this->search, fn($q) => $q->where('codice_articolo', 'like', "%{$this->search}%"))
                    ->orderBy('data_movimento', 'desc')
                    ->paginate(15);

        return view('livewire.movimentazioni.index', [
            'movimentazioni' => $items,
        ])->layout('layouts.app');
    }
}

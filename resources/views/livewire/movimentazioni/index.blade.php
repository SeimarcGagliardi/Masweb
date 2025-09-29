<div>
  <div class="flex justify-between mb-4">
    <input wire:model.debounce.300ms="search" type="text" placeholder="Cerca articolo..." class="border rounded px-3 py-2">
    <a href="{{ route('movimentazioni.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
      <i class="fas fa-plus mr-1"></i> Nuovo
    </a>
  </div>

  <table class="min-w-full bg-white shadow rounded">
    <thead>
      <tr>
        <th class="px-4 py-2">Data</th>
        <th class="px-4 py-2">Articolo</th>
        <th class="px-4 py-2">Qtà</th>
        <th class="px-4 py-2">Tipo</th>
        <th class="px-4 py-2">Componente</th>
        <th class="px-4 py-2">Azioni</th>
      </tr>
    </thead>
    <tbody>
      @foreach($movimentazioni as $m)
      <tr class="border-t">
        <td class="px-4 py-2">{{ $m->data_movimento->format('d/m/Y H:i') }}</td>
        <td class="px-4 py-2">{{ $m->codice_articolo }}</td>
        <td class="px-4 py-2">{{ $m->quantita }}</td>
        <td class="px-4 py-2">{{ ucfirst($m->tipo_movimento) }}</td>
        <td class="px-4 py-2">{{ $m->componente }}</td>
        <td class="px-4 py-2">
          <a href="{{ route('movimentazioni.edit', $m->id) }}" class="text-blue-500 hover:underline">
            <i class="fas fa-edit"></i>
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="mt-4">
    {{ $movimentazioni->links() }}
  </div>
</div>

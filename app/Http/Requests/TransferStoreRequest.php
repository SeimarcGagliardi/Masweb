<?php // app/Http/Requests/TransferStoreRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferStoreRequest extends FormRequest {
  public function authorize(): bool { return $this->user()?->can('movimenti.transfer') ?? false; }
  public function rules(): array {
    return [
      'origine.magazzino_id' => ['required','integer','exists:magazzini,id','different:destinazione.magazzino_id'],
      'destinazione.magazzino_id' => ['required','integer','exists:magazzini,id'],
      'righe' => ['required','array','min:1'],
      'righe.*.articolo_id' => ['required','integer','exists:articoli,id'],
      'righe.*.qta' => ['required','numeric','gt:0'],
      'righe.*.lotto' => ['nullable','string'],
    ];
  }
  public function messages(): array {
    return [
      'origine.magazzino_id.required' => 'Seleziona un magazzino di origine.',
      'destinazione.magazzino_id.required' => 'Seleziona un magazzino di destinazione.',
      'righe.required' => 'Aggiungi almeno una riga articolo.',
    ];
  }
}

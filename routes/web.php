<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Movimenti\TransferWizard;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');


    Route::redirect('/movimentazioni', '/movimenti/trasferimento'); // ⬅️ aggiungi questa riga
    
    Route::get('/', fn() => view('dashboard'))->name('dashboard');
    
    Route::get('/movimenti/trasferimento', TransferWizard::class)->middleware(['auth'])
         ->can('movimenti.transfer')   // se non hai ancora auth/permessi, commenta temporaneamente
        ->name('movimenti.transfer');
require __DIR__.'/auth.php';

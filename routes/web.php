<?php

use App\Livewire\Movimenti\{CaricoWizard, ContoLavoroWizard, ScaricoWizard, TransferWizard};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/profile', 'profile')->name('profile');

    Route::redirect('/movimentazioni', '/movimenti/trasferimento')->name('movimenti.index');

    Route::get('/movimenti/trasferimento', TransferWizard::class)->name('movimenti.transfer');
    Route::get('/movimenti/carico', CaricoWizard::class)->name('movimenti.carico');
    Route::get('/movimenti/scarico', ScaricoWizard::class)->name('movimenti.scarico');

    Route::get('/conto-lavoro', ContoLavoroWizard::class)->name('conto-lavoro.wizard');
});

require __DIR__.'/auth.php';

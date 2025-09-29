<?php // app/Http/Middleware/DebugbarForAdmins.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Barryvdh\Debugbar\Facades\Debugbar;

class DebugbarForAdmins
{
    public function handle(Request $request, Closure $next)
    {
        // Abilita SOLO in local + utenti con ruolo Amministratore
        if (app()->environment('local') && auth()->check() && auth()->user()->hasRole('Amministratore')) {
            Debugbar::enable();
        } else {
            Debugbar::disable();
        }
        return $next($request);
    }
}

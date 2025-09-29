<?php // database/seeders/RolesAndPermissionsSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder {
  public function run(): void {
    $perms = [
      'movimenti.create','movimenti.transfer','movimenti.cancel',
      'carico','scarico','conto_lavoro','export'
    ];
    foreach($perms as $p){ Permission::findOrCreate($p); }

    $operatore = Role::findOrCreate('Operatore');
    $capo = Role::findOrCreate('Capo Magazzino');
    $admin = Role::findOrCreate('Amministratore');

    $operatore->givePermissionTo(['movimenti.create','movimenti.transfer','carico','scarico']);
    $capo->givePermissionTo($perms);
    $admin->givePermissionTo(Permission::all());
  }
}

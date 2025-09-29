<?php // app/Policies/MovimentoPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\Movimento;

class MovimentoPolicy {
  public function create(User $user): bool {
    return $user->can('movimenti.create');
  }
  public function transfer(User $user): bool {
    return $user->can('movimenti.transfer');
  }
}

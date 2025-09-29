<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    if (Schema::hasTable('tipo_movimento')) {
      // Crea (se non esiste) tabella ponte
      if (!Schema::hasTable('tipo_movimento_map')) {
        DB::statement("
          CREATE TABLE tipo_movimento_map (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            tipo_mov_id BIGINT UNSIGNED NOT NULL,
            enum_tipo ENUM('CARICO','SCARICO','TRASF','USC_CL','ENT_CL') NOT NULL,
            UNIQUE KEY uk_tipo_mov_id (tipo_mov_id)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
      }

      // Prova a mappare per nome 'codice' o 'descrizione' se esistono
      $rows = DB::select("SHOW COLUMNS FROM tipo_movimento LIKE 'codice'");
      $hasCodice = !empty($rows);
      $rows = DB::select("SHOW COLUMNS FROM tipo_movimento LIKE 'descrizione'");
      $hasDescr = !empty($rows);

      $tipi = ['CARICO','SCARICO','TRASF','USC_CL','ENT_CL'];
      foreach ($tipi as $t) {
        $where = $hasCodice ? "LOWER(codice)=LOWER(?)" : ($hasDescr ? "LOWER(descrizione)=LOWER(?)" : null);
        if (!$where) continue;
        $row = DB::selectOne("SELECT id FROM tipo_movimento WHERE $where LIMIT 1", [$t]);
        if ($row) {
          DB::insert("INSERT IGNORE INTO tipo_movimento_map (tipo_mov_id, enum_tipo) VALUES (?,?)", [$row->id, $t]);
        }
      }
    }
  }

  public function down(): void {
    if (Schema::hasTable('tipo_movimento_map')) {
      DB::statement("DROP TABLE tipo_movimento_map");
    }
  }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    // MySQL / MariaDB syntax; per PostgreSQL adattare CASE.
    DB::statement("
      CREATE OR REPLACE VIEW view_giacenze AS
      SELECT
        m.articolo_id,
        COALESCE(m.magazzino_dest, m.magazzino_orig) AS magazzino_id,
        SUM(
          CASE
            WHEN m.tipo IN ('CARICO','ENT_CL') THEN m.qta
            WHEN m.tipo='TRASF' AND m.magazzino_dest IS NOT NULL THEN m.qta
            ELSE 0
          END
          -
          CASE
            WHEN m.tipo IN ('SCARICO','USC_CL') THEN m.qta
            WHEN m.tipo='TRASF' AND m.magazzino_orig IS NOT NULL THEN m.qta
            ELSE 0
          END
        ) AS qta_disponibile
      FROM movimenti m
      GROUP BY m.articolo_id, COALESCE(m.magazzino_dest, m.magazzino_orig)
    ");
  }

  public function down(): void {
    DB::statement("DROP VIEW IF EXISTS view_giacenze");
  }
};

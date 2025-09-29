<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
  public function up(): void {
    DB::statement("
      CREATE OR REPLACE VIEW view_giacenze_articolo AS
      SELECT g.articolo_id, SUM(g.qta_disponibile) AS qta_disponibile
      FROM view_giacenze g
      GROUP BY g.articolo_id
    ");
  }
  public function down(): void {
    DB::statement("DROP VIEW IF EXISTS view_giacenze_articolo");
  }
};

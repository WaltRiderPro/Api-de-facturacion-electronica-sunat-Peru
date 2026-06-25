<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('boletas', function (Blueprint $table) {

      if (!Schema::hasColumn('boletas', 'metodo_envio')) {
        $table->string('metodo_envio', 20)
          ->default('individual')
          ->after('moneda');
      }

      if (!Schema::hasColumn('boletas', 'daily_summary_id')) {
        $table->unsignedBigInteger('daily_summary_id')
          ->nullable()
          ->after('client_id')
          ->index();
      }

      if (!Schema::hasColumn('boletas', 'mto_igv_gratuitas')) {
        $table->decimal('mto_igv_gratuitas', 12, 2)
          ->default(0)
          ->after('mto_oper_gratuitas');
      }

      if (!Schema::hasColumn('boletas', 'mto_base_ivap')) {
        $table->decimal('mto_base_ivap', 12, 2)
          ->default(0)
          ->after('mto_igv');
      }

      if (!Schema::hasColumn('boletas', 'mto_ivap')) {
        $table->decimal('mto_ivap', 12, 2)
          ->default(0)
          ->after('mto_base_ivap');
      }
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('boletas', function (Blueprint $table) {

      if (Schema::hasColumn('boletas', 'metodo_envio')) {
        $table->dropColumn('metodo_envio');
      }

      if (Schema::hasColumn('boletas', 'daily_summary_id')) {
        $table->dropColumn('daily_summary_id');
      }

      if (Schema::hasColumn('boletas', 'mto_igv_gratuitas')) {
        $table->dropColumn('mto_igv_gratuitas');
      }

      if (Schema::hasColumn('boletas', 'mto_base_ivap')) {
        $table->dropColumn('mto_base_ivap');
      }

      if (Schema::hasColumn('boletas', 'mto_ivap')) {
        $table->dropColumn('mto_ivap');
      }
    });
  }
};

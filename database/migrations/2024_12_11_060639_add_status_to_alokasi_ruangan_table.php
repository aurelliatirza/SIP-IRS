<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToAlokasiRuanganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alokasi_ruangan', function (Blueprint $table) {
            $table->string('status')->default('belum_disetujui')->after('tahun_ajaran');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('alokasi_ruangan', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}


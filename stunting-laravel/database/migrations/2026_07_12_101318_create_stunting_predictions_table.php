<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stunting_predictions', function (Blueprint $table) {
            $table->id();
            $table->string('nama_balita')->nullable();
            $table->integer('usia_bulan');
            $table->string('jenis_kelamin');
            $table->float('berat_lahir_kg');
            $table->float('panjang_lahir_cm');
            $table->string('asi_eksklusif');
            $table->float('protein_harian');
            $table->integer('frekuensi_makan');
            $table->float('tinggi_ibu_cm');
            $table->integer('riwayat_diare');
            $table->float('pendapatan_keluarga');
            $table->string('sanitasi_layak');
            $table->string('imunisasi_lengkap');
            $table->float('risk_score');
            $table->tinyInteger('prediction_code');
            $table->string('prediction_status');
            $table->float('probability_stunting_percent')->nullable();
            $table->string('predicted_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stunting_predictions');
    }
};
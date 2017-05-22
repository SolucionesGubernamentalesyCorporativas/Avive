<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_afiliado');
            $table->string('curp_afiliado');
            $table->string('edad_afiliado');
            $table->string('domicilio_afiliado');
            $table->string('rfc_afiliado');
            $table->string('correo_electronico_afiliado');
            $table->string('nacionalidad_afiliado')->nullable();
            $table->string('nombre_representante')->nullable();
            $table->string('numero_escritura')->nullable();
            $table->string('fecha_escritura')->nullable();
            $table->string('nombre_notario')->nullable();
            $table->string('numero_notaria')->nullable();
            $table->boolean('notario_titular')->nullable();
            $table->string('estado_municipio')->nullable();
            $table->string('domicilio_avive')->nullable();
            $table->string('correo_electronico_avive')->nullable();
            $table->string('nombre_proyecto')->nullable();
            $table->integer('participantes')->nullable();
            $table->string('periodo_promo')->nullable();
            $table->integer('num_apariciones')->nullable();
            $table->string('exten_max')->nullable();
            $table->string('caracteres')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contratos');
    }
}

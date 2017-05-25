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
            $table->integer('declaracion_representante')->nullable();
            $table->string('cuenta_bancaria')->nullable();
            $table->string('cuenta_clabe')->nullable();
            $table->string('nacionalidad_afiliado')->nullable();
            $table->string('denominacion_contrato')->nullable();
            $table->string('nombre_representante')->nullable()->default("HERENDIDA RODRÍGUEZ MUÑOZ");
            $table->string('numero_escritura')->nullable();
            $table->string('fecha_escritura')->nullable();
            $table->string('nombre_notario')->nullable();
            $table->string('numero_notaria')->nullable();
            $table->boolean('notario_titular')->nullable();
            $table->string('estado_municipio')->nullable();
            $table->string('domicilio_avive')->nullable()->default("Arturo Ibañez 2B Colonia Barrio La Concepción, Delegación Coyoacán en la Ciudad de México, CP 04020");
            $table->string('version')->nullable()->default("2017");
            $table->string('correo_electronico_avive')->nullable()->default("contacto@happinessatwork.mx");
            $table->string('nombre_proyecto')->nullable()->default("HAPPINESS AT WORK");
            $table->integer('participantes')->nullable();
            $table->string('periodo_promo')->nullable();
            $table->integer('num_apariciones')->nullable();
            $table->string('exten_max')->nullable();
            $table->string('caracteres')->nullable();
            $table->string('duracion_contrato')->nullable();
            $table->string('fecha_inicio_contrato')->nullable();
            $table->string('fecha_fin_contrato')->nullable();
            $table->boolean('terminado')->default(0);
            $table->bigInteger('membresia_id');
            $table->bigInteger('pago_id');
            $table->string('url_curp');
            $table->string('url_rfc');
            $table->string('url_comprobante');
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
